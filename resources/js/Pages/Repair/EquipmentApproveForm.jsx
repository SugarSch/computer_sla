import React from 'react';
import { useForm, Link, Head, router } from '@inertiajs/react';

export default function EquipmentApproval({ repair, equipmentItems }) {
    // const { post, processing } = useForm();
    const { data, setData, post, processing, errors } = useForm({
        status: 'pending'
    });

    const handleStatus = (id, status) => {
        if (confirm(`ยืนยันการ${status === 'approved' ? 'อนุมัติ' : 'ปฏิเสธ'}รายการนี้?`)) {
            router.post(`/equipment-status-update/${id}`, {
                status: status
            });
        }
    };

    return (
        <div className="container py-5">
            <Head title="อนุมัติอุปกรณ์" />
            <div className="d-flex justify-content-between mb-4">
                <h4>จัดการรายการอุปกรณ์: {repair.title}</h4>
                <Link href={ "/repair/" + repair.id} className="btn btn-secondary">กลับ</Link>
            </div>

            <div className="card shadow-sm">
                <div className="table-responsive">
                    <table className="table table-hover mb-0">
                        <thead className="table-light">
                            <tr>
                                <th>ชื่ออุปกรณ์</th>
                                <th>ราคาประเมิน</th>
                                <th>ผู้ขอเบิก</th>
                                <th>สถานะ</th>
                                <th className="text-end">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            {equipmentItems.map((item) => (
                                <tr key={item.id}>
                                    <td>{item.item_name}</td>
                                    <td>{Number(item.cost).toLocaleString()} บาท</td>
                                    <td>{item.requested_user?.username}</td>
                                    <td>
                                        <span className={`badge bg-${item.status === 'approved' ? 'success' : (item.status === 'rejected' ? 'danger' : 'warning')}`}>
                                            {item.status}
                                        </span>
                                    </td>
                                    <td className="text-end">
                                        {item.status === 'pending' && (
                                            <>
                                                <button 
                                                    onClick={() => handleStatus(item.id, 'approved')}
                                                    className="btn btn-success btn-sm me-2"
                                                    disabled={processing}
                                                >อนุมัติ</button>
                                                <button 
                                                    onClick={() => handleStatus(item.id, 'rejected')}
                                                    className="btn btn-danger btn-sm"
                                                    disabled={processing}
                                                >ปฏิเสธ</button>
                                            </>
                                        )}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    );
}