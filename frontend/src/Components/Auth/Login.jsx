import { useForm } from "react-hook-form";

import { FaUser, FaLock } from "react-icons/fa";

import InputField from "../Auth/InputField";
import { AuthButton } from "../Common/buttons";
import { checkAuth, login } from "../../Api/Api";
import { useContext, useEffect, useState } from "react";
import { AuthContext } from "../../Context/AuthContext";
import { useNavigate } from "react-router-dom";

const Login = ({ isLoggedIn }) => {
  const [isLogginIn, setIsLoggingIn] = useState(true);
  const { setAuthUser, authUser } = useContext(AuthContext);
  const navigate = useNavigate();
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset,
  } = useForm();

  const onSubmit = async (data) => {
    console.log(data);
    try {
      await login(data);
      const response1 = await checkAuth();
      setAuthUser(response1.data.role);
    } catch (error) {
      console.error("Login failed", error);
      setAuthUser(null);
    } finally {
      setIsLoggingIn(false);
      reset();
    }
  };

  useEffect(() => {
    if (authUser == "Admin") {
      navigate("/home");
    }
  }, [authUser]);

  return (
    <form className="w-full max-w-[300px]" onSubmit={handleSubmit(onSubmit)}>
      <InputField
        icon={FaUser}
        type="text"
        placeholder="Username or Email"
        autocomplete="username"
        name="identifier"
        validation={{
          required: "Username/email is required",
        }}
        register={register}
        errors={errors.identifier}
      />

      <InputField
        icon={FaLock}
        type="password"
        placeholder="Password"
        autocomplete="current-password"
        name="password"
        validation={{
          required: "Password is required",
        }}
        register={register}
        errors={errors.password}
      />

      <AuthButton isLoggedIn={isLoggedIn} isSubmitting={isSubmitting} />
    </form>
  );
};

export default Login;

// import { FaUser, FaLock } from "react-icons/fa";
// import InputField from "../Auth/InputField";

// const Login = ({ formData, handleChange, handleSubmit }) => {

//   return (
//     <form className="w-full max-w-[300px]" onSubmit={handleSubmit}>
//       <InputField
//         icon={FaUser}
//         type="text"
//         placeholder="Username or Email"
//         name="identifier" // Ensure this matches the key in formData
//         value={formData.identifier}
//         onChange={handleChange}
//         autocomplete="username"
//       />

//       <InputField
//         icon={FaLock}
//         type="password"
//         placeholder="Password"
//         name="password" // Ensure this matches the key in formData
//         value={formData.password}
//         onChange={handleChange}
//         autocomplete="current-password"
//       />

//       <button
//         type="submit"
//         className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
//       >
//         Login
//       </button>
//     </form>
//   );
// };

// export default Login;

{
  /* <button
        type="submit"
        className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
      >
        {isSubmitting ? (
          <>
            <Loader2 className="h-5 w-5 animate-spin" />
            Loading...
          </>
        ) : (
          "Log in"
        )}
      </button> */
}
