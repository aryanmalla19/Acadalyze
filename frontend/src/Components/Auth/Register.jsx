import { useForm } from "react-hook-form";
import InputField from "../Auth/InputField";
import { leftFields, rightFields } from "../Fields/Fields";
import { AuthButton } from "../Common/buttons";
import { register as registerUser } from "../../Api/Api";
import { useNavigate } from "react-router-dom";

const Register = ({ isLoggedIn }) => {
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset,
  } = useForm();
  const navigate = useNavigate();

  const onSubmit = async (data) => {
    try {
      const response = await registerUser(data);
      console.log("registration successful:", response);
      reset();
      alert("Registration Successful");
      navigate("/home");
    } catch (error) {
      console.log("Failed:", error.message);
      alert(error.message);
    }
  };

  return (
    <form
      className="w-full max-w-[700px] grid grid-cols-1 md:grid-cols-2 gap-4"
      onSubmit={handleSubmit(onSubmit)}
    >
      {/* Left Fields */}
      <div className="flex flex-col gap-1.5">
        {leftFields.map((field) => (
          <InputField
            key={field.name}
            icon={field.icon}
            type={field.type}
            placeholder={field.placeholder}
            name={field.name}
            autocomplete={field.name}
            validation={field.validation}
            register={register}
            errors={errors[field.name]}
          />
        ))}
      </div>

      {/* Right Fields */}
      <div className="flex flex-col gap-1.5">
        {rightFields.map((field) => (
          <InputField
            key={field.name}
            icon={field.icon}
            type={field.type}
            placeholder={field.placeholder}
            name={field.name}
            autocomplete={field.name}
            validation={field.validation}
            register={register}
            errors={errors[field.name]}
          />
        ))}
      </div>

      {/* Submit Button */}

      <div className="col-span-1 md:col-span-2">
        <AuthButton isLoggedIn={isLoggedIn} isSubmitting={isSubmitting} />
      </div>
    </form>
  );
};

export default Register;
