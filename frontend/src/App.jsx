import React from "react";

import { Route, Routes } from "react-router-dom";

import Home from "./Pages/Home";
import Page1 from "./Pages/Page1";
import Page2 from "./Pages/Page2";
import Page3 from "./Pages/Page3";
import Page4 from "./Pages/Page4";

import Sidebar from "./Components/Sidebar/Sidebar";

const App = () => {
  return (
    <div className="flex h-screen overflow-hidden">
      {/* <div className="fixed inset-0 z-0">
        <div className="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-300 to-blue-900 opacity-80" />
        <div className="absolute inset-0 backdrop-blur-sm" />
      </div> */}

      <Sidebar />

      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/page1" element={<Page1 />} />
        <Route path="/page2" element={<Page2 />} />
        <Route path="/page3" element={<Page3 />} />
        <Route path="/page4" element={<Page4 />} />
      </Routes>
    </div>
  );
};

export default App;
