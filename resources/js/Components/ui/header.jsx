import { IconosEnum } from "@/emun/icons.enum";
import { getIcon } from "@/helpers/icons";


const Header = () => {
  return (
    <div className="md:flex md:flex-row md:justify-between m-auto  bg-blue-700 py-3 text-white px-2">
      <div className="flex md:gap-28 gap-3 items-center justify-between flex-col md:flex-row">
        <div className="logo flex items-center gap-3">
          
          {getIcon(IconosEnum.LOGO)}
          {getIcon(IconosEnum.LINE)}
          
        <div className="sofwareName">
         {getIcon(IconosEnum.EUREKAWHITE)}
        </div>
        </div>


       {/* <ul className="flex gap-4 items-center bg-blue-800 rounded-full py-1 px-2">
          <li className="px-10 py-1 rounded-full">Inicio</li>
          <li className="px-10 py-1 rounded-full bg-blue-500">Gestion</li>
          </ul>*/}
      </div>
      {/* //TODO: Lado derecho */}
      <div className="flex items-center gap-6 justify-center mt-4 md:mt-0">
        <div className="border-white rounded-full border p-1 relative">
          <div className="w-5 h-5 bg-red-600 absolute flex items-center justify-center rounded-full left-6 bottom-5">
            1
          </div>
          {getIcon(IconosEnum.NOTIFICACIONES)}
        </div>
        <div className="flex items-center gap-2">
          <p className="border-white rounded-full border p-1">
            {getIcon(IconosEnum.USUARIO)}
          </p>
          <span className="flex items-center gap-1">
            Martin López Guadaira {getIcon(IconosEnum.FLECHA_ABAJO)}
          </span>
        </div>
      </div>
    </div>
  );
};

export default Header;
