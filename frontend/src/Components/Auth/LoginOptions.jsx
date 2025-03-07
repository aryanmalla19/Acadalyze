import React from "react";
import SocialIcon from "./SocialIcon";

import { FaGoogle, FaFacebook, FaTwitter } from "react-icons/fa";

const LoginOptions = () => {
  return (
    <div>
      <p className="text-center text-sm text-gray-500 mb-4 font-semibold">
        or login with
      </p>
      <div className="flex justify-center gap-4 mb-4">
        <SocialIcon icon={FaGoogle} color="text-red-500" />
        <SocialIcon icon={FaFacebook} color="text-blue-600" />
        <SocialIcon icon={FaTwitter} color="text-blue-400" />
      </div>
    </div>
  );
};

export default LoginOptions;
