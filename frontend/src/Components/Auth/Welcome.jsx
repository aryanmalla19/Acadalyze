import React from "react";

const Welcome = () => {
  return (
    <div
      className="bg-[#7494ec] text-white p-10 md:w-2/3 w-full flex flex-col justify-center items-center 
            rounded-b-[100px] md:rounded-r-[150px] md:rounded-l-none transition-all duration-500"
    >
      <h1 className="font-bold text-4xl text-center mb-6">Hello, Welcome!</h1>
      <p className="font-semibold text-xl mb-6">Don't have an account?</p>
      <button className="p-2 border-2 border-white w-30 rounded-[10px] hover:bg-white hover:text-[#7494ec] transition-all cursor-pointer font-semibold">
        Register
      </button>
    </div>
  );
};

export default Welcome;
