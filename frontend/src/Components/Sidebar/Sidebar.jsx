import React from "react";
import { Link } from "react-router-dom";
import Menu from "./Menu";
import logo from "../../assets/logo.png";

const Sidebar = () => {
  return (
    <div>
      <Link to="/home">
        <header className="flex items-center justify-center lg:justify-start gap-2">
          <div>
            <img src={logo} alt="logo" />{" "}
          </div>

          <span className="hidden lg:block">Acadalyze</span>
        </header>
      </Link>

      <Menu />
    </div>
  );
};

export default Sidebar;
