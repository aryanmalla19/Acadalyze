import React from "react";

import { Navigate, Route, Routes } from "react-router-dom";

import Page1 from "./AdminPage/Dashboard/UserCard";
import Page2 from "./AdminPage/Dashboard/CountCard";
import Page3 from "./AdminPage/Dashboard/Attendence";
import Page4 from "./AdminPage/Dashboard/EventCalender";
import Sidebar from "./Components/Sidebar/Sidebar";

import Home from "./AdminPage/Dashboard/Home";
import Auth from "./AdminPage/Auth";
import MainLayout from "./Layout/MainLayout";
import useVerifyAuth from "./CustoomHook/useVerifyAuth";
import PageLoader from "./Components/Common/PageLoader";
import TeacherList from "./Components/TeacherPage/TeacherList";
import StudentList from "./Components/StudentPage/StudentList";
import ParentList from "./Components/ParentsPage/ParentsList";

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
            <Route path="/list/teachers" element={<TeacherList />} />
            <Route path="/list/students" element={<StudentList />} />
            <Route path="/list/parents" element={<ParentList />} />
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
