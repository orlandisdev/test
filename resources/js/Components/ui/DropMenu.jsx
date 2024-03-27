import { getIcon } from "@/helpers/icons";
import { Link } from "@inertiajs/react";

const DropMenu = ({ data, isOpen }) => {
    return (
        <div className={` flex flex-col`}>
            {data
                ? data.map((asset) => (
                      <Link
                        href={asset.url}
                          className={`px-2 hover:cursor-pointer hover:transition transition-all`}
                          key={asset.id}
                          style={{
                              height: "70px",
                          }}
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
