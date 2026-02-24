import React from "react";
import { useForm, Link } from '@inertiajs/react';

function Assign({ repair, technicians, page_title }) {
    const { data, setData, post, errors, processing } = useForm({
        assigned_to: ''
    });

    const submit = (e) => {
        e.preventDefault();
        post("/repair-assign/" + repair.id);
    };

    return (
        <div className="container pt-5">
            <h3 className="text-center mb-4">{page_title}</h3>
            
            <div className="card shadow p-4 col-md-8 mx-auto">
                <div className="mb-3">
                    <strong>หัวข้อแจ้งซ่อม:</strong> {repair.title} <br/>
                    <strong>ผู้แจ้ง:</strong> {repair.user ?.username || '-'}
                </div>

                <form onSubmit={submit}>
                    <div className="mb-3">
                        <label className="form-label">เลือกช่างผู้รับผิดชอบ</label>
                        <select 
                            className={`form-select ${errors.assigned_to ? 'is-invalid' : ''}`}
                            value={data.assigned_to}
                            onChange={e => setData('assigned_to', e.target.value)}
                        >
                            <option value="">-- กรุณาเลือกรายชื่อ --</option>
                            {technicians.map(tech => (
                                <option key={tech.id} value={tech.id}>
                                    {tech.username} ({tech.role?.label || 'Tech'})
                                </option>
                            ))}
                        </select>
                        {errors.assigned_to && <div className="invalid-feedback">{errors.assigned_to}</div>}
                    </div>

                    <div className="d-flex justify-content-between pt-3">
                        <Link href={`/repair/${repair.id}`} className="btn btn-secondary">
                            ยกเลิก
                        </Link>
                        <button type="submit" className="btn btn-primary" disabled={processing}>
                            บันทึก
                        </button>
                    </div>
                </form>
            </div>
        </div>
    );
}

export default Assign;