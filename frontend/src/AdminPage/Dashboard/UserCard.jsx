import React from "react";
import {
  FaUserGraduate,
  FaChalkboardTeacher,
  FaUserFriends,
} from "react-icons/fa";

const UserCard = ({ numbers, type }) => {
  const getIcon = () => {
    if (type === "Students")
      return <FaUserGraduate className="text-blue-500 text-4xl" />;
    if (type === "Teachers")
      return <FaChalkboardTeacher className="text-white text-4xl" />;
    if (type === "Parents")
      return <FaUserFriends className="text-purple-500 text-4xl" />;
    return null;
  };
  return (
    <div className="rounded-2xl odd:bg-skyBlue even:bg-green text-gray-500 flex flex-col items-center shadow-md p-4 md:w-60 lg:w-80 xl:w-96">
      <div className="flex items-center gap-2">
        {getIcon()}
        <span className="text-sm font-semibold text-black">2024/25</span>
      </div>
      <h1 className="text-xl font-bold">{numbers}</h1>
      <h2 className="text-lg tracking-wide">{type}</h2>
    </div>
  );
};

export default UserCard;
