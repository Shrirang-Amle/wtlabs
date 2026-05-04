import { useEffect, useState } from "react";

const THEMES = {
  light: {
    label: "Light Mode",
    background: "#f4efe6",
    text: "#1f2933",
    card: "#fffaf3",
    accent: "#b45309",
    button: "#1f2933",
    buttonText: "#fdfaf6",
  },
  dark: {
    label: "Dark Mode",
    background: "#111827",
    text: "#f9fafb",
    card: "#1f2937",
    accent: "#f59e0b",
    button: "#f9fafb",
    buttonText: "#111827",
  },
};

const getInitialTheme = () => {
  if (typeof window === "undefined") {
    return "light";
  }

  const savedTheme = localStorage.getItem("theme");
  return savedTheme === "dark" ? "dark" : "light";
};

function App() {
  const [theme, setTheme] = useState(getInitialTheme);
  const currentTheme = THEMES[theme];

  useEffect(() => {
    localStorage.setItem("theme", theme);
    document.body.style.backgroundColor = currentTheme.background;
    document.body.style.color = currentTheme.text;
  }, [theme, currentTheme.background, currentTheme.text]);

  const toggleTheme = () => {
    setTheme((previousTheme) =>
      previousTheme === "light" ? "dark" : "light"
    );
  };

  return (
    <main
      className="app-shell"
      style={{
        backgroundColor: currentTheme.background,
        color: currentTheme.text,
      }}
    >
      <section
        className="theme-card"
        style={{ backgroundColor: currentTheme.card }}
      >
        <p className="eyebrow">React Hooks Theme Switcher</p>
        <h1>Current Theme: {currentTheme.label}</h1>
        <p className="description">
          Use the button below to toggle between light mode and dark mode.
        </p>

        <button
          type="button"
          className="toggle-button"
          onClick={toggleTheme}
          style={{
            backgroundColor: currentTheme.button,
            color: currentTheme.buttonText,
            borderColor: currentTheme.accent,
          }}
        >
          Switch to {theme === "light" ? "Dark Mode" : "Light Mode"}
        </button>
      </section>
    </main>
  );
}

export default App;
