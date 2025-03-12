import { useState } from "react";

import LoginForm from "../Components/Auth/Login";
import RegisterForm from "../Components/Auth/Register";
import Welcome from "../Components/Auth/Welcome";
import CredentialsOptions from "../Components/Auth/CredentialsOptions";
import LoginOptions from "../Components/Auth/LoginOptions";

const Auth = () => {
  const [isLoggedIn, setIsLoggedIn] = useState(true);

  const toggleAuthMode = () => setIsLoggedIn(!isLoggedIn);

  return (
    <div className="relative flex justify-center items-center min-h-screen bg-gradient-to-r from-[#e2e2e2] to-[#c9d6ff]">
      <div className="relative w-full max-w-[1100px] md:h-[1000px] xl:h-[610px] bg-white rounded-xl shadow-xl flex flex-col md:flex-row overflow-x-hidden">
        <div
          className={`bg-[#7494ec] text-white p-10 w-full flex flex-col justify-center items-center rounded-b-[100px] transition-all duration-500 ${
            isLoggedIn
              ? "md:w-2/3 md:rounded-r-[150px] md:rounded-l-none"
              : "md:w-1/2 md:rounded-l-[150px] md:rounded-r-none"
          } ${isLoggedIn ? "" : "translate-x-full"}`}
        >
          <Welcome isLoggedIn={isLoggedIn} toggleAuthMode={toggleAuthMode} />
        </div>

        <div
          className={`w-full md:w-1/2 flex flex-col justify-center items-center p-8 bg-white transition-all duration-500 ${
            isLoggedIn ? "" : "-translate-x-full"
          }`}
        >
          <h2 className="text-4xl font-bold mb-6 text-gray-700">
            {isLoggedIn ? "Login" : "Register"}
          </h2>
          {isLoggedIn ? (
            <LoginForm isLoggedIn={isLoggedIn} />
          ) : (
            <RegisterForm isLoggedIn={isLoggedIn} />
          )}
          <CredentialsOptions isLoggedIn={isLoggedIn} />
          <LoginOptions />
        </div>
      </div>
    </div>
  );
};

export default Auth;

// const [formData, setFormData] = useState({
//   identifier: "",
//   password: "",
//   email: "",
//   username: "",
//   first_name: "",
//   last_name: "",
//   address: "",
//   phone_number: "",
//   parent_phone_number: "",
//   date_of_birth: "",
// });

// const toggleAuthMode = () => setIsLoggedIn(!isLoggedIn);

// const handleChange = (e) => {
//   setFormData({ ...formData, [e.target.name]: e.target.value });
// };

// const handleSubmit = async (e) => {
//   e.preventDefault();
//   console.log("Form Data:", formData);

//   try {
//     if (isLoggedIn) {
//       const response = await login(formData.identifier, formData.password);
//       localStorage.setItem("authToken", response.data.token);
//       console.log(response.data.token);
//       console.log("Login successful:", response);
//     } else {
//       const response = await register(formData);
//       console.log("Registration successful:", response);
//       setIsLoggedIn(true); // Switch to login mode after registration
//     }
//   } catch (error) {
//     console.error("Error:", error);
//     alert(error.message || "An error occurred. Please try again.");
//   }
// };

//  {
//    isLoggedIn ? (
//      <LoginForm
//        formData={formData}
//        handleChange={handleChange}
//        handleSubmit={handleSubmit}
//      />
//    ) : (
//      <RegisterForm
//        formData={formData}
//        handleChange={handleChange}
//        handleSubmit={handleSubmit}
//      />
//    );
//  }
