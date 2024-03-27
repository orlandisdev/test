import Submenu from '@/Components/ui/submenu'
import Guest from '@/Layouts/GuestLayout'
import { Head } from '@inertiajs/react'

const Departamentos = () => {
  return (
    <>
    <Guest>
        <Head title="Departamentos" />

        <main className="ml-3">

            <h1 className='font-bold text-3xl block'>
                Departamentos
            </h1>

            <Submenu />

        </main>

    </Guest>
</>
  )
}

export default Departamentos
