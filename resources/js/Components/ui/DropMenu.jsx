import { getIcon } from "@/helpers/icons";

const DropMenu = ({ data, isOpen }) => {
    data.map((asset) => console.log(asset.id));
    return (
        <div className={`bg-blue-600 text-white flex flex-col`}>
            {data
                ? data.map((asset) => (
                      <button
                          className={`${
                              !isOpen ? "px-5" : "px-2"
                          } hover:bg-blue-800 hover:cursor-pointer hover:transition transition-all`}
                          key={asset.id}
                          style={{
                              height: "70px",
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
                      </button>
                  ))
                : "Loading"}
        </div>
    );
};

export default DropMenu;
