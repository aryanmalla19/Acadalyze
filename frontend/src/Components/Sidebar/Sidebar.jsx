import React, { useState } from "react";

import { motion, AnimatePresence } from "framer-motion";
import { Link } from "react-router-dom";

import { BarChart2, Menu, Users } from "lucide-react";

export const SIDEBAR_ITEMS = [
  {
    name: "Home",
    icon: BarChart2,
    color: "#6366f1",
    path: "/",
  },
  {
    name: "Page1",
    icon: BarChart2,
    color: "#8b5cf6",
    path: "/page1",
  },
  {
    name: "Page2",
    icon: Users,
    color: "#ec4899",
    path: "/page2",
  },
  {
    name: "Page3",
    icon: BarChart2,
    color: "#10b981",
    path: "/page3",
  },
  {
    name: "Page4",
    icon: BarChart2,
    color: "#f59e0b",
    path: "/page4",
  },
];
const Sidebar = () => {
  const [isSidebarOpen, setIsSidebarOpen] = useState(true);
  return (
    <motion.div
      className={`relative z-10 transition-all duration-300 ease-in-out flex-shrink-0 ${
        isSidebarOpen ? "w-64" : "w-20"
      }`}
      animate={{ width: isSidebarOpen ? 256 : 80 }}
    >
      <div className="h-full p-4 flex flex-col border-r border-gray-700">
        {/* Menu */}
        <div className="flex items-center justify-between">
          <motion.button
            whileHover={{ scale: 1.1, cursor: "pointer" }}
            whileTap={{ scale: 0.9 }}
            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
            className="p-2 rounded-full hover:bg-gray-300 transition-colors max-w-fit"
          >
            <Menu size={23} />
          </motion.button>
          <AnimatePresence>
            {isSidebarOpen && (
              <motion.span
                className="ml-4 whitespace-nowrap font-bold text-2xl"
                initial={{ opacity: 0, width: 0 }}
                animate={{ opacity: 1, width: "auto" }}
                exit={{ opacity: 0, width: 0 }}
                transition={{ duration: 0.2, delay: 0.3 }}
              >
                Acadalyze
              </motion.span>
            )}
          </AnimatePresence>
        </div>

        {/* Nav List */}
        <nav className="mt-8 flex-grow">
          {SIDEBAR_ITEMS.map((item) => (
            <Link key={item.path} to={item.path}>
              <motion.div
                className={`flex items-center p-4 text-sm font-medium rounded-lg hover:bg-gray-200 transition-colors mb-2`}
              >
                <item.icon
                  size={20}
                  style={{ color: item.color, minWidth: "20px" }}
                />

                <AnimatePresence>
                  {isSidebarOpen && (
                    <motion.span
                      className="ml-4 whitespace-nowrap"
                      initial={{ opacity: 0, width: 0 }}
                      animate={{ opacity: 1, width: "auto" }}
                      exit={{ opacity: 0, width: 0 }}
                      transition={{ duration: 0.2, delay: 0.3 }}
                    >
                      {item.name}
                    </motion.span>
                  )}
                </AnimatePresence>
              </motion.div>
            </Link>
          ))}
        </nav>
      </div>
    </motion.div>
  );
};

export default Sidebar;
