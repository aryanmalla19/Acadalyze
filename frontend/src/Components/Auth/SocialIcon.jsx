import React from "react";

const SocialIcon = ({ icon: Icon, color }) => {
  return (
    <>
      {Icon && (
        <Icon
          className={`${color} cursor-pointer hover:scale-110 transition`}
        />
      )}
    </>
  );
};

export default SocialIcon;
