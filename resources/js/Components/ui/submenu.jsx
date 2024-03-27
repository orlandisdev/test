import { useRemember } from "@inertiajs/react";
import { useState } from "react";

const Submenu = () => {
  const [active, setActive] = useRemember("1");

  return (
    <ul className="ml-2 flex gap-6 items-start mt-3">
      <button
        className={`${
          active === "1" ? "text-blue-800 border-b-blue-800 border-b-2" : ""
        }`}
        onClick={() => setActive("1")}
      >
        Todas
      </button>
      <button
        className={`${
          active === "2" ? "text-blue-800 border-b-blue-800 border-b-2" : ""
        }`}
        onClick={() => setActive("2")}
      >
        Facturas pendientes
      </button>
      <button
        className={`${
          active === "3" ? "text-blue-800 border-b-blue-800 border-b-2" : ""
        }`}
        onClick={() => setActive("3")}
      >
        Facturas vencidas
      </button>
      <button
        className={`${
          active === "4" ? "text-blue-800 border-b-blue-800 border-b-2" : ""
        }`}
        onClick={() => setActive("4")}
      >
        Facturas rechazadas
      </button>
    </ul>
  );
};

export default Submenu;
