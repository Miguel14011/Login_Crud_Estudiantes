import { Head, Link, useForm } from '@inertiajs/react';
import CamposEstudiante, { valoresIniciales } from '../../Components/CamposEstudiante';

export default function Register() {
    const form = useForm(valoresIniciales);

    const submit = (e) => {
        e.preventDefault();
        form.post('/register', { onFinish: () => form.reset('password', 'password_confirmation') });
    };

    return (
        <div className="flex min-h-screen items-center justify-center px-4 py-8">
            <Head title="Registro de estudiante" />

            <form onSubmit={submit} className="w-full max-w-2xl space-y-5 rounded-lg bg-white p-8 shadow">
                <div className="text-center">
                    <h1 className="text-2xl font-semibold">Registro de estudiante</h1>
                    <p className="mt-1 text-sm text-slate-500">Crea tu cuenta con tus datos académicos</p>
                </div>

                <CamposEstudiante form={form} />

                <button
                    type="submit"
                    disabled={form.processing}
                    className="w-full rounded bg-indigo-600 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {form.processing ? 'Creando cuenta…' : 'Registrarme'}
                </button>

                <p className="text-center text-sm text-slate-500">
                    ¿Ya tienes cuenta?{' '}
                    <Link href="/login" className="font-medium text-indigo-600 hover:underline">
                        Inicia sesión
                    </Link>
                </p>
            </form>
        </div>
    );
}
