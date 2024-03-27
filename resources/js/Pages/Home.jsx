import Submenu from '@/Components/ui/submenu';
import Guest from '@/Layouts/GuestLayout';
import { Link, Head } from '@inertiajs/react';
import {
    HoverCard,
    HoverCardContent,
    HoverCardTrigger,
  } from "@/components/ui/hover-card"
export default function Welcome({ auth, laravelVersion, phpVersion }) {
    return (
        <>
            <Guest>
                <Head title="Inicio" />

                <main className="ml-3 bg-red-600">

                    <h1 className='font-bold text-3xl block'>
                        Facturas
                    </h1>

                    <Submenu />

                </main>

                <HoverCard>
  <HoverCardTrigger>Hover</HoverCardTrigger>
  <HoverCardContent>
    The React Framework – created and maintained by @vercel.
  </HoverCardContent>
</HoverCard>

            </Guest>
        </>
    );
}
