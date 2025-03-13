import { LogOut } from "lucide-react";
import React, { useContext } from "react";
import { AuthContext } from "../../Context/AuthContext";
import { logout } from "../../Api/Api";

const Navbar = () => {
  const { setAuthUser } = useContext(AuthContext);

  const handleLogout = async () => {
    await logout();
    setAuthUser(null);
  };

  return (
    <div className="bg-primary p-4 flex justify-between">
      <div>Hello</div>
      <div>
        <button
          className="flex gap-2 items-center text-white"
          onClick={handleLogout}
        >
          <LogOut className="size-5" />
          <span className="hidden sm:inline">Logout</span>
        </button>
      </div>
    </div>
  );
};

export default Navbar;
