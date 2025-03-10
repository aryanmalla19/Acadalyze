import React from "react";
import Sidebar from "../Components/Sidebar/Sidebar";
import { Outlet } from "react-router-dom";
import Navbar from "../Components/Navbar/Navbar";

const MainLayout = () => {
  return (
    <div className="flex min-h-screen">
      <div className="w-[14%] md:w-[8%] lg:w-[16%] xl:w-[14%] p-4 border-r border-slate-500">
        <Sidebar />
      </div>
      <div className="w-[86%] md:w-[92%] lg:w-[84%] xl:w-[86%] bg-[#f7f8fa] overflow-scroll">
        <Navbar />
        <Outlet />
      </div>
    </div>
  );
};

export default MainLayout;
