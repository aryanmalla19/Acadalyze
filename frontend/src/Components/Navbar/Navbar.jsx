import { LogOut } from "lucide-react";
import React from "react";
import { AuthContext } from "../../Context/AuthContext";
import { CiSearch } from "react-icons/ci";
import { LuMessageCircleMore } from "react-icons/lu";
import { IoIosNotifications } from "react-icons/io";

const Navbar = () => {
  return (
    <div className="flex items-center justify-between p-4">
      {/* SEARCH BAR */}
      <div className="hidden md:flex items-center gap-2 bg-gray-200 px-3 py-2 rounded-lg">
        <CiSearch />
        <input
          type="text"
          placeholder="Search..."
          className="bg-transparent outline-none"
        />
      </div>
      {/*  ICONS AND USERS */}
      <div className="flex items-center gap-6">
        <div className="bg-gray-200 rounded-full w-10 h-10 cursor-pointer flex justify-center items-center">
          <LuMessageCircleMore className="w-[20px] h-[20px]" />
        </div>
        <div className="relative">
          <div className="bg-gray-200 rounded-full w-10 h-10 cursor-pointer flex justify-center items-center">
            <IoIosNotifications className="w-[20px] h-[20px]" />
          </div>
          <div className="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold w-5 h-5 flex justify-center items-center rounded-full">
            1
          </div>
        </div>

        <div className="flex flex-col">
          <span className="text-sm font-medium leading-3">Neetu Rai</span>
          <span className="text-[10px] text-gray-500 text-right">Admin</span>
        </div>
        <div className="w-10 h-10 shadow-sm shadow-gray-400 rounded-full">
          <img
            src="https://res.cloudinary.com/jerrick/image/upload/d_642250b563292b35f27461a7.png,f_jpg,q_auto,w_720/67347bab768161001d967d2a.png"
            className="rounded-full w-full h-full object-cover"
          />
        </div>
      </div>
    </div>
  );
};

export default Navbar;
