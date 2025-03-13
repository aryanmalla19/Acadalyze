import React, { useContext, useEffect, useState } from "react";

import { Navigate, Route, Routes } from "react-router-dom";

import Page1 from "./Pages/Page1";
import Page2 from "./Pages/Page2";
import Page3 from "./Pages/Page3";
import Page4 from "./Pages/Page4";
import Sidebar from "./Components/Sidebar/Sidebar";

import Home from "./Pages/Home";
import Auth from "./Pages/Auth";
import MainLayout from "./Layout/MainLayout";
import useVerifyAuth from "./CustoomHook/useVerifyAuth";
import PageLoader from "./Components/Common/PageLoader";

const App = () => {
  const { authUser, isCheckingAuth } = useVerifyAuth();

  if (isCheckingAuth) return <PageLoader />;

  console.log(authUser);

  return (
    <div>
      <Routes>
        <Route path="/" element={<Navigate to="/auth" />} />
        <Route
          path="/auth"
          element={authUser ? <Navigate to="/home" /> : <Auth />}
        />

        {authUser ? (
          <Route element={<MainLayout />}>
            <Route path="/home" element={<Home />} />
          </Route>
        ) : (
          <Route path="*" element={<Navigate to="/auth" />} />
        )}
      </Routes>
    </div>
  );
};

export default App;

{
  /* <Route element={authUser ? <MainLayout /> : <Navigate to="/auth" />}>
          <Route
            path="/home"
            element={authUser ? <Home /> : <Navigate to="/auth" />}
          />
        </Route> */
}
