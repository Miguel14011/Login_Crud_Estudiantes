import { Head, Link, useForm } from '@inertiajs/react';

export default function Login() {
    const { data, setData, post, processing, errors } = useForm({
        username: '',
        password: '',
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();
        post('/login', { onFinish: () => setData('password', '') });
    };

    return (
        <div className="flex min-h-screen items-center justify-center px-4">
            <Head title="Iniciar sesión" />

            <form onSubmit={submit} className="w-full max-w-sm space-y-5 rounded-lg bg-white p-8 shadow">
                <div className="text-center">
                    <h1 className="text-2xl font-semibold">Iniciar sesión</h1>
                    <p className="mt-1 text-sm text-slate-500">Ingresa para gestionar los estudiantes</p>
                </div>

                <div>
                    <label htmlFor="username" className="mb-1 block text-sm font-medium">
                        Usuario
                    </label>
                    <input
                        id="username"
                        type="text"
                        autoComplete="username"
                        autoFocus
                        value={data.username}
                        onChange={(e) => setData('username', e.target.value)}
                        className="w-full rounded border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"
                    />
                    {errors.username && <p className="mt-1 text-sm text-red-600">{errors.username}</p>}
                </div>

                <div>
                    <label htmlFor="password" className="mb-1 block text-sm font-medium">
                        Contraseña
                    </label>
                    <input
                        id="password"
                        type="password"
                        autoComplete="current-password"
                        value={data.password}
                        onChange={(e) => setData('password', e.target.value)}
                        className="w-full rounded border border-slate-300 px-3 py-2 focus:border-indigo-500 focus:outline-none"
                    />
                    {errors.password && <p className="mt-1 text-sm text-red-600">{errors.password}</p>}
                </div>

                <label className="flex items-center gap-2 text-sm">
                    <input
                        type="checkbox"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                    />
                    Recordarme
                </label>

                <button
                    type="submit"
                    disabled={processing}
                    className="w-full rounded bg-indigo-600 py-2 font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                >
                    {processing ? 'Ingresando…' : 'Ingresar'}
                </button>

                <p className="text-center text-sm text-slate-500">
                    ¿No tienes cuenta?{' '}
                    <Link href="/register" className="font-medium text-indigo-600 hover:underline">
                        Regístrate
                    </Link>
                </p>
            </form>
        </div>
    );
}
