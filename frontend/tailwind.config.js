/** @type {import('tailwindcss').Config} */
export default {
  content: ["./index.html", "./src/**/*.{js,ts,jsx,tsx}"],
  theme: {
    extend: {
      colors: {
        primary: "#7494ec",
        skyBlue: "#C3EBFA",
        lightSkyBlue: "#CFCEFF",
        green: "#6ACF65",
        customYellow: ""
      },
    },
  },
  plugins: [],
};
