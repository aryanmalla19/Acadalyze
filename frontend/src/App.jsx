import React, { useContext, useEffect } from "react";

import { Route, Routes } from "react-router-dom";

import Page1 from "./Pages/Page1";
import Page2 from "./Pages/Page2";
import Page3 from "./Pages/Page3";
import Page4 from "./Pages/Page4";
import Sidebar from "./Components/Sidebar/Sidebar";

import Home from "./Pages/Home";
import Auth from "./Pages/Auth";
import MainLayout from "./Layout/MainLayout";
import { AuthContext } from "./Context/AuthContext";
import { checkAuth } from "./Api/Api";

const App = () => {
  const { authUser, setAuthUser } = useContext(AuthContext);

  useEffect(() => {
    checkAuth();
  }, [checkAuth]);

  return (
    <div>
      <Routes>
        <Route path="/" element={<Auth />} />
        <Route element={<MainLayout />}>
          <Route path="/home" element={<Home />} />
        </Route>
      </Routes>
    </div>
  );
};

export default App;
