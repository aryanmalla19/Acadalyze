import React from "react";
import {
  FaUser,
  FaLock,
  FaGoogle,
  FaFacebook,
  FaTwitter,
} from "react-icons/fa";

// Reusable Input Component
const InputField = ({ icon: Icon, type, placeholder }) => (
  <div className="mb-4">
    <div className="flex items-center bg-gray-100 rounded-md p-2">
      <input
        type={type}
        placeholder={placeholder}
        required
        className="w-full bg-transparent focus:outline-none"
      />
      <Icon className="text-gray-500 mr-2" />
    </div>
  </div>
);

// Reusable Social Icon Component
const SocialIcon = ({ icon: Icon, color }) => (
  <Icon className={`${color} cursor-pointer hover:scale-110 transition`} />
);

const LoginSignup = () => {
  return (
    <div className="relative flex justify-center items-center min-h-screen bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff]">
      <div className="relative w-full max-w-[900px] md:h-[700px] xl:h-[600px] bg-white rounded-[30px] shadow-xl flex flex-col md:flex-row overflow-hidden">
        {/* Left Side: Welcome Section */}
        <div
          className="bg-[#7494ec] text-white p-10 md:w-2/3 w-full flex flex-col justify-center items-center 
            rounded-b-[100px] md:rounded-r-[150px] md:rounded-l-none transition-all duration-500"
        >
          <h1 className="font-bold text-4xl text-center mb-6">
            Hello, Welcome!
          </h1>
          <p className="font-semibold text-xl mb-6">Don't have an account?</p>
          <button className="p-2 border-2 border-white w-30 rounded-[10px] hover:bg-white hover:text-[#7494ec] transition-all cursor-pointer font-semibold">
            Register
          </button>
        </div>

        {/* Right Side: Login Form */}
        <div className="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-white">
          <h2 className="text-4xl font-bold mb-6 text-gray-700">Login</h2>
          <form className="w-full max-w-[300px]">
            <InputField icon={FaUser} type="text" placeholder="Username" />
            <InputField icon={FaLock} type="password" placeholder="Password" />

            <div className="flex justify-between mb-4 text-sm text-gray-600 font-semibold">
              <label className="flex items-center">
                <input type="checkbox" className="mr-2" />
                Remember me
              </label>
              <a href="#" className="text-red-500 hover:text-red-600">
                Forgot Password?
              </a>
            </div>

            <button
              type="submit"
              className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
            >
              Login
            </button>

            <p className="text-center text-sm text-gray-500 mb-4 font-semibold">
              or login with
            </p>
            <div className="flex justify-center gap-4 mb-4">
              <SocialIcon icon={FaGoogle} color="text-red-500" />
              <SocialIcon icon={FaFacebook} color="text-blue-600" />
              <SocialIcon icon={FaTwitter} color="text-blue-400" />
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};

export default LoginSignup;
