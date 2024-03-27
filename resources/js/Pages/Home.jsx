import Submenu from '@/Components/ui/submenu';
import Guest from '@/Layouts/GuestLayout';
import { Link, Head } from '@inertiajs/react';

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    
    return (
        <>
            <Guest>
                <Head title="Inicio" />

                <main className="ml-3">

                    <h1 className='font-bold text-3xl block'>
                        Facturas
                    </h1>

                    <Submenu />

                </main>

            </Guest>
        </>
    );
}
