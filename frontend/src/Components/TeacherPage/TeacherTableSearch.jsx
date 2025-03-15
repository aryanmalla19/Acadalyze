import React from "react";
import { CiSearch } from "react-icons/ci";
import { FaFilter, FaSort, FaPlus } from "react-icons/fa";

const actionButtons = [
  {
    icon: <FaFilter />,
    title: "Filter",
    bgColor: "bg-gray-200",
    hoverColor: "hover:bg-gray-300",
    textColor: "text-gray-700",
  },
  {
    icon: <FaSort />,
    title: "Sort",
    bgColor: "bg-gray-200",
    hoverColor: "hover:bg-gray-300",
    textColor: "text-gray-700",
  },
  {
    icon: <FaPlus />,
    title: "Add",
    bgColor: "bg-blue-500",
    hoverColor: "hover:bg-blue-600",
    textColor: "text-white",
  },
];

const TeacherTableSearch = ({ query, setQuery }) => {
  return (
    <div className="flex flex-wrap items-center gap-3">
      {/* SEARCH BAR */}
      <div className="w-full md:w-auto flex items-center gap-2 bg-gray-100 px-3 py-2 rounded-full">
        <CiSearch className="text-gray-500" />
        <input
          type="text"
          placeholder="Search..."
          value={query}
          onChange={(e) => setQuery(e.target.value)}
          className="bg-transparent outline-none w-full md:w-auto"
        />
      </div>

      {/* ACTION BUTTONS */}
      <div className="flex items-center gap-3">
        {actionButtons.map((button, index) => (
          <button
            key={index}
            className={`p-2 rounded-full transition ${button.bgColor} ${button.hoverColor}`}
            title={button.title}
          >
            <span className={`${button.textColor}`}>{button.icon}</span>
          </button>
        ))}
      </div>
    </div>
  );
};

export default TeacherTableSearch;
