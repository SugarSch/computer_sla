import React from "react";
import { Link } from '@inertiajs/react';

function Dashboard({ repairs, add_new_repair }){
    console.log(repairs);
    return (
        <div className="container pt-5">
            <h3 className="mb-3">รายการแจ้งซ่อม</h3>
            <div className="pt-3 pb-3 text-right">
                {add_new_repair && (
                    <Link href="/repair-create" className="btn btn-primary">แจ้งซ่อมใหม่</Link>
                )}
            </div>
            <div className="table-responsive">
                <table className="table">
                    <thead>
                        <tr>
                            <th>เรื่อง</th>
                            <th>สถานะ</th>
                            <th>ผู้แจ้ง</th>
                            <th>ช่างผู้รับผิดชอบ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        {repairs.data.map(r => (
                            <tr key={r.id}>
                                <td>{r.title}</td>
                                <td>{r.repair_status.label}</td>
                                <td>{r.user.username}</td>
                                <td>{r.assigned_to_user?.username ?? "-"}</td>
                                <td>
                                    <Link
                                        href={`/repair/${r.id}`}
                                        className="btn btn-sm btn-info"
                                    >
                                        ดูรายละเอียด
                                    </Link>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            
            {/* pagination */}
            <div className="d-flex gap-1">
                {repairs.links.map((link, i) => (
                    <Link
                        key={i}
                        href={link.url || '#'}
                        className={`btn btn-sm ${link.active ? 'btn-primary' : 'btn-outline-primary'}`}
                        disabled={!link.url}
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                ))}
            </div>
        </div>
    )
}

export default Dashboard;