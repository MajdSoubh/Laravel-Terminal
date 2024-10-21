let commands = [];
let index = 0;
function getPreviousCommand() {
  if (index) {
    return commands[--index];
  }
  if (commands.length) {
    return commands[0];
  }
  return false;
}
function getNextCommand() {
  if (index < commands.length - 1) {
    return commands[++index];
  }
  if (commands.length) {
    return commands[commands.length - 1];
  }
  return false;
}
// Function to parse ANSI escape sequences (including \u001b) to HTML with CSS
function parseAnsiToCss(text) {
  // Map of ANSI escape codes to corresponding CSS classes
  const ansiToCssMap = {
    0: "</span>", // Reset all styles
    1: '<span class="ansi-bold" style="font-weight: bold;">', // Bold
    2: '<span class="ansi-dim" style="opacity: 0.75;">', // Dim (less bright)
    3: '<span class="ansi-italic" style="font-style: italic;">', // Italic
    4: '<span class="ansi-underline" style="text-decoration: underline;">', // Underline
    5: '<span class="ansi-blink" style="text-decoration: blink;">', // Blink (not widely supported)
    7: '<span class="ansi-inverse" style="filter: invert(1);">', // Inverse/reverse (swap foreground/background)
    8: '<span class="ansi-hidden" style="visibility: hidden;">', // Hidden text
    9: '<span class="ansi-strikethrough" style="text-decoration: line-through;">', // Strikethrough

    // Foreground (Text) Colors
    30: '<span class="ansi-black" style="color: black;">', // Black text
    31: '<span class="ansi-red" style="color: #e06c75;">', // Red text (saturated)
    32: '<span class="ansi-green" style="color: #98c379;">', // Green text (saturated)
    33: '<span class="ansi-yellow" style="color: #e5c07b;">', // Yellow text (saturated)
    34: '<span class="ansi-blue" style="color: #61afef;">', // Blue text (saturated)
    35: '<span class="ansi-magenta" style="color: #c678dd;">', // Magenta text (saturated)
    36: '<span class="ansi-cyan" style="color: #56b6c2;">', // Cyan text (saturated)
    37: '<span class="ansi-white" style="color: #f1f1f0;">', // White text (saturated)
    38: '<span class="ansi-custom-color">', // Custom foreground color (handled separately)
    39: '<span style="color: inherit;">', // Reset to default text color

    // Background Colors
    40: '<span class="ansi-bg-black" style="background-color: black;">', // Black background
    41: '<span class="ansi-bg-red" style="background-color: #e06c75;">', // Red background (saturated)
    42: '<span class="ansi-bg-green" style="background-color: #98c379;">', // Green background (saturated)
    43: '<span class="ansi-bg-yellow" style="background-color: #e5c07b;">', // Yellow background (saturated)
    44: '<span class="ansi-bg-blue" style="background-color: #61afef; display:inline-block">', // Blue background (saturated)
    45: '<span class="ansi-bg-magenta" style="background-color: #c678dd;">', // Magenta background (saturated)
    46: '<span class="ansi-bg-cyan" style="background-color: #56b6c2;">', // Cyan background (saturated)
    47: '<span class="ansi-bg-white" style="background-color: #abb2bf;">', // White background (saturated)
    48: '<span class="ansi-custom-bg-color">', // Custom background color (handled separately)
    49: '<span style="background-color: inherit;">', // Reset to default background color

    // Bright Foreground (Text) Colors (90–97)
    90: '<span class="ansi-bright-black" style="color: #5c6370;">', // Bright black (saturated, gray)
    91: '<span class="ansi-bright-red" style="color: #f77669;">', // Bright red (saturated)
    92: '<span class="ansi-bright-green" style="color: #b1dba4;">', // Bright green (saturated)
    93: '<span class="ansi-bright-yellow" style="color: #f3f99d;">', // Bright yellow (more saturated)
    94: '<span class="ansi-bright-blue" style="color: #82b1ff;">', // Bright blue (saturated)
    95: '<span class="ansi-bright-magenta" style="color: #de91fc;">', // Bright magenta (saturated)
    96: '<span class="ansi-bright-cyan" style="color: #89ddff;">', // Bright cyan (saturated)
    97: '<span class="ansi-bright-white" style="color: #ffffff;">', // Bright white

    // Bright Background Colors (100–107)
    100: '<span class="ansi-bg-bright-black" style="background-color: #5c6370;">', // Bright black background (gray)
    101: '<span class="ansi-bg-bright-red" style="background-color: #f77669;">', // Bright red background
    102: '<span class="ansi-bg-bright-green" style="background-color: #b1dba4;">', // Bright green background
    103: '<span class="ansi-bg-bright-yellow" style="background-color: #f3f99d;">', // Bright yellow background
    104: '<span class="ansi-bg-bright-blue" style="background-color: #82b1ff;">', // Bright blue background
    105: '<span class="ansi-bg-bright-magenta" style="background-color: #de91fc;">', // Bright magenta background
    106: '<span class="ansi-bg-bright-cyan" style="background-color: #89ddff;">', // Bright cyan background
    107: '<span class="ansi-bg-bright-white" style="background-color: #ffffff;">', // Bright white background

    "0m": "</span>", // Reset
  };
  let openTags = 0;
  // Replace ANSI codes with HTML tags
  let result = text.replace(
    /[\u001b]*[\x1b]*\[([0-9;]+)m/g,
    (match, codes, s) => {
      const codeList = codes.split(";");

      let html = "";
      while (openTags--) {
        html += "</span>";
      }

      // Iterate over each code and map it to the corresponding CSS
      codeList.forEach((code) => {
        html += ansiToCssMap[code] || "";
      });

      openTags = codeList.length;

      return html;
    }
  );

  while (openTags--) {
    result += "</span>";
  }

  return result;
}

function fetchCurrentWorkingDirectory() {
  const apiEndPoint = document
    .querySelector('meta[name="directory"]')
    .getAttribute("content");
  return sendRequest("get", apiEndPoint).then((response) => response.directory);
}

function sendRequest(type, endPoint, payload = {}, header = {}) {
  const csrfToken = document
    .querySelector('meta[name="csrf"]')
    .getAttribute("content");

  let data = {};

  if (type === "get" || type == "head") {
    data = {};
  } else {
    data = { body: JSON.stringify(payload) };
  }

  return fetch(endPoint, {
    method: type,
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
      ...header,
    },
    ...data,
  })
    .then((response) => response.json())
    .then((response) => {
      return response;
    })
    .catch((exception) => {
      console.log(exception);
    });
}

function insertNewInputElement(currentDirectory) {
  const terminals = document.querySelectorAll(".xterm");
  const currentTerminal = terminals[terminals.length - 1];

  currentTerminal.classList.remove("active");
  // Get the prompt
  const prompt = document
    .querySelector('meta[name="prompt"]')
    .getAttribute("content");

  const newTerminal = currentTerminal.cloneNode(true);
  newTerminal.children[0].innerText = currentDirectory + " " + prompt;
  newTerminal.children[1].innerText = "";
  newTerminal.classList.add("active");

  // Append created element to the end of the main container
  const container = document.getElementById("app");
  container.appendChild(newTerminal);
  newTerminal.scrollIntoView({ behavior: "smooth" });
}

function getActiveInputElement() {
  const inputElements = document.getElementsByClassName("xterm-input");
  return inputElements[inputElements.length - 1];
}

function displayResult(content) {
  // Create element and assign unique class name to it
  const outputElement = document.createElement("div");
  outputElement.classList.add("output");
  content.output.forEach((val) => {
    let parsedText = parseAnsiToCss(val);
    outputElement.innerHTML += parsedText + "<br/>";
  });
  // Append created element to the end of the main container
  const container = document.getElementById("app");
  container.appendChild(outputElement);
  outputElement.scrollIntoView({ behavior: "smooth" });
}
function clear() {
  fetchCurrentWorkingDirectory().then((directory) => {
    insertNewInputElement(directory);
    const xterms = document.querySelectorAll(".xterm:not(:last-child)");
    const outputs = document.querySelectorAll(".output");
    xterms.forEach((t) => t.remove());
    outputs.forEach((o) => o.remove());
  });
}
function executeCommand(command) {
  if (command === "clear" || command === "clr") {
    clear();
  } else {
    const apiEndPoint = document
      .querySelector('meta[name="command"]')
      .getAttribute("content");

    sendRequest("post", apiEndPoint, { command }).then((response) => {
      displayResult(response);
      insertNewInputElement(response.directory);
    });
  }

  storeCommand(command);
}
function storeCommand(command) {
  commands.push(command);
  index = commands.length;
}
function getKeyPressed(event) {
  let keyPressed = "";

  // List of keys to ignore
  const ignoredKeys = [
    "CapsLock",
    "Tab",
    "F1",
    "F2",
    "F3",
    "F4",
    "F5",
    "F6",
    "F7",
    "F8",
    "F9",
    "F10",
    "F11",
    "F12",
    "PageUp",
    "PageDown",
    "Home",
    "End",
    "Insert",
    "Delete",
    "NumLock",
    "ScrollLock",
    "Control",
    "Alt",
    "ArrowRight",
    "ArrowLeft",
  ];

  if (ignoredKeys.includes(event.key)) {
    keyPressed = "";
  } else if (event.shiftKey) {
    switch (event.code) {
      case "Digit1":
        keyPressed = "!";
        break;
      case "Digit2":
        keyPressed = "@";
        break;
      case "Digit3":
        keyPressed = "#";
        break;
      case "Digit4":
        keyPressed = "$";
        break;
      case "Digit5":
        keyPressed = "%";
        break;
      case "Digit6":
        keyPressed = "^";
        break;
      case "Digit7":
        keyPressed = "&";
        break;
      case "Digit8":
        keyPressed = "*";
        break;
      case "Digit9":
        keyPressed = "(";
        break;
      case "Digit0":
        keyPressed = ")";
        break;
      case "Minus":
        keyPressed = "_";
        break;
      case "Equal":
        keyPressed = "+";
        break;
      case "BracketLeft":
        keyPressed = "{";
        break;
      case "BracketRight":
        keyPressed = "}";
        break;
      case "Backslash":
        keyPressed = "|";
        break;
      case "Semicolon":
        keyPressed = ":";
        break;
      case "Quote":
        keyPressed = '"';
        break;
      case "Comma":
        keyPressed = "<";
        break;
      case "Period":
        keyPressed = ">";
        break;
      case "Slash":
        keyPressed = "?";
        break;
      default:
        keyPressed = "";
    }
  } else if (event.key == "ArrowUp") {
    const terminal = getActiveInputElement();

    let lastCommand = getPreviousCommand();
    if (lastCommand) {
      terminal.innerText = lastCommand;
    }
  } else if (event.key == "ArrowDown") {
    const terminal = getActiveInputElement();
    let nextCommand = getNextCommand();
    if (nextCommand) {
      terminal.innerText = nextCommand;
    }
  } else {
    keyPressed = event.key;
  }

  return keyPressed;
}

function registerEvents() {
  document.addEventListener("paste", function (event) {
    event.preventDefault();

    // Get current active input field
    const terminal = getActiveInputElement();

    // Insert the clipboard data (text) to the current input field.
    terminal.innerText += event.clipboardData.getData("text");

    terminal.scrollIntoView({
      behavior: "auto",
      inline: "end",
    });
  });
  document.addEventListener("keydown", (event) => {
    // Check if Ctrl+V (Windows/Linux) or Cmd+V (macOS) is pressed for paste
    if ((event.ctrlKey || event.metaKey) && event.key === "v") {
      return;
    }
    event.preventDefault();
    const terminal = getActiveInputElement();
    const keyPressed = getKeyPressed(event);
    switch (keyPressed) {
      case "Enter":
        executeCommand(terminal.innerText);
        break;
      case "Backspace":
        terminal.innerText = terminal.innerText.slice(0, -1);
        break;
      default:
        terminal.innerText += keyPressed;
    }
    terminal.scrollIntoView({
      behavior: "auto",
      inline: "end",
    });
  });
}

fetchCurrentWorkingDirectory().then((directory) =>
  insertNewInputElement(directory)
);

registerEvents();
