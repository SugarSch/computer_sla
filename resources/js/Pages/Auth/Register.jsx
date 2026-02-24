import React from "react";
import { useForm } from '@inertiajs/react'

function Register({ departments, roles }) {
    const { data, setData, post, errors } = useForm({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
        department_id: '',
        role_id: '',
    })

    const submit = e => {
        e.preventDefault()
        post('/register')
    }

    return (
        <div className="container min-vh-100 d-flex align-items-center justify-content-center">
            <div className="col-12 col-sm-10 col-md-6 col-lg-4">
                <div className="card shadow p-4">
                    <h3 className="text-center mb-4">ฟอร์มลงทะเบียน</h3>

                    <form onSubmit={submit}>
                        <input className="form-control mb-2"
                            placeholder="ชื่อผู้เข้าใช้งาน"
                            onChange={e => setData('username', e.target.value)} />
                        {errors.username && <small className="text-danger">{errors.username}</small>}
                        <input className="form-control mb-2"
                            placeholder="อีเมล"
                            onChange={e => setData('email', e.target.value)} />
                        {errors.email && <small className="text-danger">{errors.email}</small>}

                        <input type="password" className="form-control mb-2"
                            placeholder="รหัสผ่าน"
                            onChange={e => setData('password', e.target.value)} />
                        {errors.password && <small className="text-danger">{errors.password}</small>}

                        <input type="password" className="form-control mb-2"
                            placeholder="ยืนยันรหัสผ่าน"
                            onChange={e => setData('password_confirmation', e.target.value)} />

                        <select className="form-select mb-2"
                            onChange={e => setData('department_id', e.target.value)}>
                            <option value="">เลือกแผนก</option>
                            {departments.map(d => (
                                <option key={d.id} value={d.id}>{d.name}</option>
                            ))}
                        </select>
                        {errors.department_id && <small className="text-danger">{errors.department_id}</small>}

                        <select className="form-select mb-3"
                            onChange={e => setData('role_id', e.target.value)}>
                            <option value="">เลือกสิทธิการใช้งาน</option>
                            {roles.map(r => (
                                <option key={r.id} value={r.id}>{r.label}</option>
                            ))}
                        </select>
                        {errors.role_id && <small className="text-danger">{errors.role_id}</small>}

                        <button className="btn btn-primary">ลงทะเบียน</button>
                    </form>
                </div>
            </div>
        </div>
    )
}

export default Register;