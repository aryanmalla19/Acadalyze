import { Loader2 } from "lucide-react";

export const AuthButton = ({ isSubmitting, isLoggedIn }) => {
  return (
    <button
      type="submit"
      className="w-full bg-[#7494ec] text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
    >
      {isSubmitting ? (
        <>
          <Loader2 className="h-5 w-5 animate-spin" />
          Loading...
        </>
      ) : isLoggedIn ? (
        "Log in"
      ) : (
        "Register"
      )}
    </button>
  );
};
