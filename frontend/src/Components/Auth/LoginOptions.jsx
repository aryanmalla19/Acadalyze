import React from "react";
import SocialIcon from "./SocialIcon";

import { FaGoogle, FaFacebook } from "react-icons/fa";

const LoginOptions = () => {
  return (
    <div>
      <div className="flex items-center mb-4">
        <hr className="flex-grow border-gray-500" />
        <p className="font-semibold text-black text-center mx-4">OR</p>
        <hr className="flex-grow border-gray-500" />
      </div>
      <div className="flex flex-col justify-center gap-2">
        <div className="flex items-center gap-4 border-gray-400 p-2 bg-gray-100 w-full hover:bg-gray-200 cursor-pointer">
          <SocialIcon icon={FaGoogle} color="text-red-500" />
          <p className="text-sm font-medium">Continue with Google</p>
        </div>
        <div className="flex items-center gap-4 border-gray-400 p-2 bg-gray-100 w-full hover:bg-gray-200 cursor-pointer">
          <SocialIcon icon={FaFacebook} color="text-blue-600" />
          <p className="text-sm font-medium">Continue with Facebook</p>
        </div>
      </div>
    </div>
  );
};

export default LoginOptions;
