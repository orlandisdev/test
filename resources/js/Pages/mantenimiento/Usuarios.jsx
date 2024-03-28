import Table from "@/Components/tables/Table";
import Submenu from "@/Components/ui/submenu";
import Guest from "@/Layouts/GuestLayout";
import { Head } from "@inertiajs/react";

const Usuarios = () => {
    return (
        <>
            <Guest>
                <Head title="Usuarios" />

                <main className="ml-3">
                    <h1 className="font-bold text-3xl block">Usuarios</h1>

                    <Submenu />
                    <div className="my-5 mx-2">
                        <Table />
                    </div>
                </main>
            </Guest>
        </>
    );
};

export default Usuarios;
