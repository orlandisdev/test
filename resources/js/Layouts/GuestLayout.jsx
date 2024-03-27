import ApplicationLogo from '@/Components/ApplicationLogo';
import Header from '@/Components/ui/header';
import Menu from '@/Components/ui/menu';

import { Link } from '@inertiajs/react';

export default function Guest({ children }) {
    //h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100
    return (
        <div className="">

            <div className="">
                <Header />
                <div className="h-screen flex">

                    <Menu />
                    {children}
                </div>
            </div>
        </div>
    );
}
