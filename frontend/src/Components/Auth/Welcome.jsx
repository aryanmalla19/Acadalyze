import React from "react";

const Welcome = ({ isLoggedIn, toggleAuthMode }) => {
  return (
    <div className="flex flex-col justify-center items-center">
      <h1 className="font-bold text-4xl text-center mb-6">
        {isLoggedIn ? "Hello, Welcome!" : "Welcome Back!"}
      </h1>
      <p className="font-semibold text-xl mb-6">
        {isLoggedIn ? "Don't have an account?" : "Already have an account?"}
      </p>
      <button
        onClick={toggleAuthMode}
        className="p-2 border-2 border-white w-30 rounded-[10px] hover:bg-white hover:text-[#7494ec] transition-all cursor-pointer font-semibold"
      >
        {isLoggedIn ? "Register" : "Login"}
      </button>
    </div>
  );
};

export default Welcome;
