import { Loader2 } from "lucide-react";

export const AuthButton = ({ isLoggedIn, isLoggingIn }) => {
  return (
    <button
      type="submit"
      className="w-full bg-primary text-white py-2 rounded-md mb-4 hover:bg-blue-600 transition font-semibold"
    >
      {isLoggingIn ? (
        <div className="flex items-center justify-center gap-5">
          <Loader2 className="h-5 w-5 animate-spin" />
          Loading...
        </div>
      ) : isLoggedIn ? (
        "Log in"
      ) : (
        "Register"
      )}
    </button>
  );
};
