// Command history handling
let commandHistory = [],
  commandIndex = 0;

const getPreviousCommand = () =>
  commandHistory[--commandIndex] || commandHistory[0] || false;
const getNextCommand = () =>
  commandHistory[++commandIndex] ||
  commandHistory[commandHistory.length - 1] ||
  false;

// Parse ANSI escape sequences into HTML with inline styles
function ansiToHtml(text) {
  const ansiMap = {
    0: "</span>",
    1: '<span style="font-weight: bold;">',
    2: '<span style="opacity: 0.75;">',
    3: '<span style="font-style: italic;">',
    4: '<span style="text-decoration: underline;">',
    5: '<span style="text-decoration: blink;">',
    7: '<span style="filter: invert(1);">',
    8: '<span style="visibility: hidden;">',
    9: '<span style="text-decoration: line-through;">',
    30: '<span style="color: black;">',
    31: '<span style="color: #e06c75;">',
    32: '<span style="color: #98c379;">',
    33: '<span style="color: #e5c07b;">',
    34: '<span style="color: #61afef;">',
    35: '<span style="color: #c678dd;">',
    36: '<span style="color: #56b6c2;">',
    37: '<span style="color: #f1f1f0;">',
    40: '<span style="background-color: black;">',
    41: '<span style="background-color: #e06c75;">',
    42: '<span style="background-color: #98c379;">',
    43: '<span style="background-color: #e5c07b;">',
    44: '<span style="background-color: #61afef;">',
    45: '<span style="background-color: #c678dd;">',
    46: '<span style="background-color: #56b6c2;">',
    47: '<span style="background-color: #abb2bf;">',
    "0m": "</span>",
  };

  let openTags = 0;
  let finalResult = text.replace(/\x1b\[(\d+;?)+m/g, (match, codes) => {
    let result = "</span>".repeat(openTags);
    codes.split(";").forEach((code) => (result += ansiMap[code] || ""));
    openTags = codes.split(";").length;
    return result;
  });
  finalResult += "</span>".repeat(openTags);
  return finalResult;
}

// Fetch current working directory
function fetchCurrentDirectory() {
  const apiEndPoint = document
    .querySelector('meta[name="directory"]')
    .getAttribute("content");
  return sendRequest("get", apiEndPoint).then((response) => response.directory);
}

// Send API request
async function sendRequest(method, url, data = {}, headers = {}) {
  const csrfToken = document.querySelector('meta[name="csrf"]').content;
  const options = {
    method,
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": csrfToken,
      ...headers,
    },
    ...(method !== "get" &&
      method !== "head" && {
        body: JSON.stringify(data),
      }),
  };

  try {
    const response = await fetch(url, options);
    if (response.status === 200) return response.json();
    if (response.status === 419) return window.location.reload();
    alert("Unexpected input detected");
  } catch {
    alert("Unexpected error occurred");
  }
}

// Insert a new terminal input field
async function createNewTerminalInput(currentDir = "") {
  const terminals = document.querySelectorAll(".xterm");
  const terminal = terminals[terminals.length - 1];
  terminal.classList.remove("active");

  const promptSymbol = document.querySelector('meta[name="prompt"]').content;
  const newTerminal = terminal.cloneNode(true);

  if (!currentDir) currentDir = await fetchCurrentDirectory();
  newTerminal.children[0].innerText = `${currentDir} ${promptSymbol}`;
  newTerminal.children[1].innerText = "";
  newTerminal.classList.add("active");

  document.getElementById("app").appendChild(newTerminal);
  newTerminal.scrollIntoView({ behavior: "smooth" });
}

// Get active terminal input element
function getActiveTerminalInput() {
  const inputElements = document.getElementsByClassName("xterm-input");
  return inputElements[inputElements.length - 1];
}

// Display command result with ANSI to HTML parsing
function showCommandResult(content) {
  const outputDiv = document.createElement("div");
  outputDiv.classList.add("output");
  content.output.forEach(
    (line) => (outputDiv.innerHTML += `${ansiToHtml(line)}<br/>`)
  );

  document.getElementById("app").appendChild(outputDiv);
  outputDiv.scrollIntoView({ behavior: "smooth" });
}
// Clear terminal
async function clearTerminal() {
  await createNewTerminalInput();
  document
    .querySelectorAll(".xterm:not(:last-child)")
    .forEach((el) => el.remove());
  document.querySelectorAll(".output").forEach((el) => el.remove());
}

// Execute a terminal command
function executeTerminalCommand(command) {
  if (["clear", "clr"].includes(command)) {
    clearTerminal();
  } else if (command.trim() === "") {
    createNewTerminalInput();
  } else {
    const apiEndPoint = document
      .querySelector('meta[name="command"]')
      .getAttribute("content");

    sendRequest("post", apiEndPoint, { command }).then((response) => {
      showCommandResult(response);
      createNewTerminalInput(response.directory);
    });
  }

  saveCommandToHistory(command);
}
// Save command to history
function saveCommandToHistory(command) {
  commandHistory.push(command);
  commandIndex = commandHistory.length;
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
    "Meta",
  ];

  if (ignoredKeys.includes(event.key)) {
    keyPressed = "";
  } else if (event.shiftKey) {
    // Handle shifted special characters
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
        // Handle Shift + letter to produce uppercase letters
        if (event.key.length === 1 && event.key.match(/[A-Z]/)) {
          keyPressed = event.key.toUpperCase();
        } else {
          keyPressed = "";
        }
    }
  } else if (event.key == "ArrowUp") {
    const terminal = getActiveTerminalInput();

    let lastCommand = getPreviousCommand();
    if (lastCommand) {
      terminal.innerText = lastCommand;
    }
  } else if (event.key == "ArrowDown") {
    const terminal = getActiveTerminalInput();
    let nextCommand = getNextCommand();
    if (nextCommand) {
      terminal.innerText = nextCommand;
    }
  } else {
    keyPressed = event.key;
  }

  return keyPressed;
}
// Register event listeners for paste and keypresses
function registerEvents() {
  window.onload = () => {
    document.addEventListener("paste", function (event) {
      getActiveTerminalInput().innerText += event.clipboardData.getData("text");
      terminal.scrollIntoView({
        behavior: "auto",
        inline: "end",
      });
    });
    document.addEventListener("keydown", (event) => {
      // Check if Ctrl+V (Windows/Linux) or Cmd+V (macOS) is pressed for paste
      if ((event.ctrlKey || event.metaKey) && event.key === "v") return;
      event.preventDefault();

      const terminal = getActiveTerminalInput();
      const keyPressed = getKeyPressed(event);

      if (keyPressed === "Enter") executeTerminalCommand(terminal.innerText);
      else if (keyPressed === "Backspace")
        terminal.innerText = terminal.innerText.slice(0, -1);
      else terminal.innerText += keyPressed;

      terminal.scrollIntoView({
        behavior: "auto",
        inline: "end",
      });
    });
  };
}

// Bootstrap
createNewTerminalInput();
registerEvents();
