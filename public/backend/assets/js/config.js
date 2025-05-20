"use strict";

var base = {
  defaultFontFamily: "Overpass, sans-serif",
  primaryColor: "#1b68ff",
  secondaryColor: "#4f4f4f",
  successColor: "#3ad29f",
  warningColor: "#ffc107",
  infoColor: "#17a2b8",
  dangerColor: "#dc3545",
  darkColor: "#343a40",
  lightColor: "#f2f3f6",
};

var extend = {
  primaryColorLight: tinycolor(base.primaryColor).lighten(10).toString(),
  primaryColorLighter: tinycolor(base.primaryColor).lighten(30).toString(),
  primaryColorDark: tinycolor(base.primaryColor).darken(10).toString(),
  primaryColorDarker: tinycolor(base.primaryColor).darken(30).toString(),
};

var chartColors = [base.primaryColor, base.successColor, "#6f42c1", extend.primaryColorLighter];

var colors = {
  bodyColor: "#6c757d",
  headingColor: "#495057",
  borderColor: "#e9ecef",
  backgroundColor: "#f8f9fa",
  mutedColor: "#adb5bd",
  chartTheme: "light",
};

// Disable dark mode completely:
var dark = document.querySelector("#darkTheme");
var light = document.querySelector("#lightTheme");
var switcher = document.querySelector("#modeSwitcher");

// Always enable light theme
if (dark) dark.disabled = true;
if (light) light.disabled = false;

// Store mode as light only
localStorage.setItem("mode", "light");

// Remove or disable the modeSwitch function since no toggle needed
function modeSwitch() {
  // Do nothing or remove this function
  console.log("Dark mode disabled, only light theme active.");
}

// If you want to hide the modeSwitcher button
if (switcher) {
  switcher.style.display = "none";
}
