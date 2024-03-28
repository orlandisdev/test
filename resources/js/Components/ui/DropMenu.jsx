import { getIcon } from "@/helpers/icons";
import { Link } from "@inertiajs/react";

const DropMenu = ({ data, isOpen }) => {
    return (
        <div className={` flex flex-col`}>
            {data
                ? data.map((asset) => (
                      <Link
                        href={asset.url}
                          className={` hover:bg-blue-600 hover:cursor-pointer hover:transition transition-all py-4 rounded-lg`}
                          key={asset.id}
                         
                      >
                          <p className="flex flex-col items-center pt-4 gap-1">
                              {getIcon(asset.icon)}

                              <span
                                  className={`text-sm transition`}
                              >
                                  {asset.name }
                              </span>
                          </p>
                      </Link>
                  ))
                : "Loading"}
        </div>
    );
};

export default DropMenu;
