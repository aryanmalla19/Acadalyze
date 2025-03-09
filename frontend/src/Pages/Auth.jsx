import { FaUser, FaLock } from "react-icons/fa";
import InputField from "../Components/Auth/InputField";
import Welcome from "../Components/Auth/Welcome";
import CredentialsOptions from "../Components/Auth/CredentialsOptions";
import LoginOptions from "../Components/Auth/LoginOptions";
import { useState } from "react";
import { register, login } from "../APIservices/authService.js";

const Auth = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(true);
  const [formData, setFormData] = useState({
    usernameOrEmail: "",
    password: "",
    confirmPassword: "",
  });

  // Function to toggle between login and register
  const toggleAuthMode = () => setIsLoggedIn(!isLoggedIn);

  //handle input change
  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  //handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      if (isLoggedIn) {
        // Call login API
        const response = await login({
          usernameOrEmail: formData.usernameOrEmail,
          password: formData.password,
        });
        console.log("Login successful:", response);
      } else {
        // Call register API
        const response = await register({
          username: formData.usernameOrEmail,
          password: formData.password,
        });
        console.log("Registration successful:", response);
        // Handle successful registration (e.g., switch to login)
        setIsLoggedIn(true);
      }
    } catch (error) {
      console.error("Error:", error);
      alert("An error occurred. Please try again.");
    }
  };

  return (
    <div className="relative flex justify-center items-center min-h-screen bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff]">
      <div className="relative w-full max-w-[900px] md:h-[700px] xl:h-[600px] bg-white rounded-[30px] shadow-xl flex flex-col md:flex-row overflow-x-hidden">
        {/* Left Side: Welcome Section */}
        <div
          className={`bg-[#7494ec] text-white p-10 w-full flex flex-col justify-center items-center rounded-b-[100px] transition-all duration-500 ${
            isLoggedIn
              ? "md:w-2/3 md:rounded-r-[150px] md:rounded-l-none"
              : "md:w-1/2 md:rounded-l-[150px] md:rounded-r-none"
          } ${isLoggedIn ? "" : "translate-x-full"}`}
        >
          <Welcome isLoggedIn={isLoggedIn} toggleAuthMode={toggleAuthMode} />
        </div>

        {/* Right Side: Login or Register Form */}
        <div
          className={`w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-white transition-all duration-500 ${
            isLoggedIn ? "" : "-translate-x-full"
          }`}
        >
          <h2 className="text-4xl font-bold mb-6 text-gray-700">
            {isLoggedIn ? "Login" : "Register"}
          </h2>
          <form className="w-full max-w-[300px]" onSubmit={handleSubmit}>
            {isLoggedIn ? (
              <>
                {/* Single input for username or email */}
                <InputField
                  icon={FaUser}
                  type="text"
                  placeholder="Username or Email"
                  name="usernameOrEmail"
                  value={formData.usernameOrEmail}
                  onChange={handleChange}
                />
                <InputField
                  icon={FaLock}
                  type="password"
                  placeholder="Password"
                  name="password"
                  value={formData.password}
                  onChange={handleChange}
                />
              </>
            ) : (
              <>
                <InputField
                  icon={FaUser}
                  type="text"
                  placeholder="Username"
                  name="usernameOrEmail"
                  value={formData.usernameOrEmail}
                  onChange={handleChange}
                />
                <InputField
                  icon={FaLock}
                  type="password"
                  placeholder="Password"
                  name="password"
                  value={formData.password}
                  onChange={handleChange}
                />
                <InputField
                  icon={FaLock}
                  type="password"
                  placeholder="Confirm Password"
                  name="confirmPassword"
                  value={formData.confirmPassword}
                  onChange={handleChange}
                />
              </>
            )}

            <CredentialsOptions />

            <button
              type="submit"
              className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
            >
              {isLoggedIn ? "Login" : "Register"}
            </button>

            <LoginOptions />
          </form>
        </div>
      </div>
    </div>
  );
};

export default Auth;
