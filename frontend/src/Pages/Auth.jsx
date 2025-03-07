import { FaUser, FaLock } from "react-icons/fa";

import InputField from "../Components/Auth/InputField";
import Welcome from "../Components/Auth/Welcome";
import CredentialsOptions from "../Components/Auth/CredentialsOptions ";
import LoginOptions from "../Components/Auth/LoginOptions";

const Auth = () => {
  return (
    <div className="relative flex justify-center items-center min-h-screen bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff]">
      <div className="relative w-full max-w-[900px] md:h-[700px] xl:h-[600px] bg-white rounded-[30px] shadow-xl flex flex-col md:flex-row overflow-hidden">
        {/* Left Side: Welcome Section */}
        <Welcome />

        {/* Right Side: Login Form */}
        <div className="w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-white">
          <h2 className="text-4xl font-bold mb-6 text-gray-700">Login</h2>
          <form className="w-full max-w-[300px]">
            <InputField icon={FaUser} type="text" placeholder="Username" />
            <InputField icon={FaLock} type="password" placeholder="Password" />

            <CredentialsOptions />

            <button
              type="submit"
              className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
            >
              Login
            </button>

            <LoginOptions />
          </form>
        </div>
      </div>
    </div>
  );
};

export default Auth;
