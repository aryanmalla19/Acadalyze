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
