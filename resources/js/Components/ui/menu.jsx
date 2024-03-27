import { IconosEnum } from "@/emun/icons.enum";
import { getIcon } from "@/helpers/icons";
import { useRemember } from "@inertiajs/react";



const Menu = () => {
  const [isOpen, setIsOpen] = useRemember (true);
  const menu = [
    {
      id: 1,
      name: "Expedientes",
      active: true,
      url: "expedientes",
      icon: IconosEnum.EXPEDIENTES,
    },
    {
      id: 2,
      name: "Tareas",
      active: true,
      url: "tareas",

      icon: IconosEnum.TAREAS,
    },
    {
      id: 3,
      name: "Presupuestos",
      active: true,
      url: "",
      icon: IconosEnum.PRESUPUESTOS,
    },
    {
      id: 4,
      name: "Pedidos",
      active: true,
      url: "",

      icon: IconosEnum.PEDIDOS,
    },
    {
      id: 5,
      name: "Facturas",
      active: true,
      url: "",
      icon: IconosEnum.FACTURAS,
    },
    {
      id: 6,
      name: "Inventario",
      active: true,
      url: "",
      icon: IconosEnum.INVENTARIO,
    },
    {
      id: 7,
      name: "Calendarios",
      active: true,
      url: "",
      icon: IconosEnum.CALENDARIOS,
    },
    {
      id: 8,
      name: "Mantenimiento",
      active: true,
      url: "",
      icon: IconosEnum.MANTEINIMIENTO,
    },
  ];
  return (
    <aside className={`bg-blue-800 text-white flex flex-col`}>
      <div>
        {menu.map((asset) => (
          <div
            className={`${
              !isOpen ? "px-5" : "px-2"
            } hover:bg-blue-600 hover:cursor-pointer hover:transition transition-all`}
            key={asset.id}
            style={{
              height: "68px",
            }}
          >
            <p className="flex flex-col items-center pt-4 gap-1">
              {getIcon(asset.icon)}

              <span
                className={`text-sm ${
                  isOpen ? "opacity-1" : "opacity-0"
                } transition`}
              >
                {isOpen ? asset.name : ""}
              </span>
            </p>
          </div>
        ))}
      </div>
      <div className="flex flex-col items-center md:mt-32 mt-16">
        <div className="h-16">
          <button className="flex flex-col items-center pt-4 gap-1">
            {getIcon(IconosEnum.AYUDA)}
            {isOpen && <span className="text-sm">Ayuda</span>}
          </button>
        </div>
        <div>
          <button
            onClick={() => setIsOpen(!isOpen)}
            className="flex flex-col items-center pt-4 gap-1"
          >
            {!isOpen
              ? getIcon(IconosEnum.DERECHA)
              : getIcon(IconosEnum.IZQUIERDA)}
          </button>
        </div>
      </div>
    </aside>
  );
};

export default Menu;
