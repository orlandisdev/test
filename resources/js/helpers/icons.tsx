import { iconsData } from "@/assets/icons/iconos";

export const getIcon = (icon: string | number )  => {
  const iconos = iconsData;

  const icono = iconos[icon];

  return icono.icon;
};
