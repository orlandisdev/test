import { useEffect,useState } from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { getIcon } from '@/helpers/icons';
import { IconosEnum } from '@/emun/icons.enum';

export default function Login({ status, canResetPassword, }) {
    const { flash } = usePage().props

    const { data, setData, post, processing, errors, reset } = useForm({
        usuario: '',
        password: '',
    });
const [errores, setErrores] = useState('')


    const submit = (e) => {
        e.preventDefault();
        if(data.usuario == '' || data.password == '') return setErrores('Rellene todos los campos')
       
        post('login')
    };

    return (
        <form onSubmit={submit}>
                         
            <div className='w-full  overflow-hidden'>
            
                <Head title="Log in" />
                <div className="loginStylesManual" >
                    <div className="flex flex-col w-full justify-center md:ml-56 ml-10">
                        <div className="">
                            <h1 className='font-bold text-4xl pb-5 text-blue-500'>
                                {getIcon(IconosEnum.EUREKA)}
                                </h1>
                            <p className='font-bold text-xl pb-5'>Inicia sesión</p>
                        </div>
                        {flash.message && (
          <div className="mb-4 font-medium text-lg bg-red-600 text-white p-2 inline uppercase w-1/2">{flash.message}</div>
        )}
                        <div className='my-5'>
                            <InputLabel htmlFor="usuario" value="Usuario" />
                            <TextInput
                                id="usuario"
                                type="usuario"
                                name="usuario"
                                value={data.usuario}
                                className=" my-1 py-2 block w-1/2 border border-gray-300 "
                                autoComplete="username"
                                isFocused={true}
                                onChange={(e) => setData('usuario', e.target.value)}
                            />

                            <InputError message={errors.usuario} className="mt-2" />
                        </div>
                        <div className='my-5 '>
                            <InputLabel htmlFor="password" value="Contraseña" />
                            <TextInput
                                id="password"
                                type="password"
                                name="password"
                                value={data.password}
                                className=" my-1 py-2 block w-1/2 border-1 border-gray-300 "
                                onChange={(e) => setData('password', e.target.value)}
                            />
                            <InputError message={errors.password} className="mt-2" />
                        </div>
                        {errores && <div className="mb-4 font-medium text-sm text-red-600">{errores}</div>}

                        <PrimaryButton type='submit' className="py-3 bg-blue-600 w-1/2 justify-center " disabled={processing}>
                            Iniciar sesión
                        </PrimaryButton>
                    </div>
                    <div className="mt-6 md:mt-0">
                        <img className="h-screen md:w-full object-cover relative " src='/img/login-img.jpg' alt='Imagen principal login' />
                      <div className="fixed bottom-10 manualImageFixed ">
                        {getIcon(IconosEnum.LOGO)}
                      </div>
                    </div>
                </div>

            </div>
        </form>
    );
}
