// Popup for bencana markers
function buildMarkerPopupBencana(data) {
    const namaWilayah = [
        data.wilayah?.parent?.parent?.nama,
        data.wilayah?.parent?.nama,
        data.wilayah?.nama,
    ].filter(Boolean).join(' - ');

    const pct = data.persentase ?? 0;
    const fotoAwal = data.foto_sebelum ? ASSET_PATH + data.foto_sebelum : '';
    const fotoPulih = data.foto_sesudah ? ASSET_PATH + data.foto_sesudah : '';

    let iconImage = data.indikator?.icon;
    if (data.status === 'Mendekati') iconImage = data.indikator?.icon2;
    if (data.status === 'Atensi') iconImage = data.indikator?.icon3;
    const iconUrl = iconImage ? ASSET_PATH + iconImage : '';

    let riwayatHtml = '';
    if (data.list_perbaikan && data.list_perbaikan.length > 0) {
        let itemsHtml = data.list_perbaikan.map((perbaikan, index) => {
            const tanggal = perbaikan.tanggal ? formatDate(perbaikan.tanggal) : '-';
            return `
                <div style="padding:12px 14px;border-bottom:1px solid #e8e8e8;background:#fff;">
                    <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:6px;">
                        <div style="flex:1;">
                            <div style="font-size:12px;color:#2a4d7a;font-weight:600;margin-bottom:3px;">
                                <span style="display:inline-block;width:20px;height:20px;line-height:20px;text-align:center;background:#2a4d7a;color:#fff;border-radius:50%;font-size:10px;margin-right:6px;">${index + 1}</span>
                                ${tanggal}
                            </div>
                            <div style="font-size:13px;color:#333;margin-left:26px;margin-bottom:4px;">
                                <strong>Jumlah:</strong> ${perbaikan.jumlah ?? '-'} ${data.satuan ?? ''}
                            </div>
                            <div style="font-size:12px;color:#666;margin-left:26px;margin-bottom:4px;">
                                <strong>Pelapor:</strong> ${perbaikan.pelapor ?? '-'}
                            </div>
                            ${perbaikan.keterangan ? `<div style="font-size:12px;color:#555;margin-left:26px;font-style:italic;">"${perbaikan.keterangan}"</div>` : ''}
                        </div>
                        ${perbaikan.foto ? `<div style="margin-left:8px;"><img src="${ASSET_PATH}${perbaikan.foto}" style="width:60px;height:60px;object-fit:cover;border-radius:6px;border:2px solid #ddd;"></div>` : ''}
                    </div>
                </div>`;
        }).join('');

        const totalPerbaikan = data.list_perbaikan.reduce((sum, p) => sum + (parseFloat(p.jumlah) || 0), 0);

        riwayatHtml = `
            <div style="border-top:2px solid #eee;background:#fafafa;">
                <div style="padding:10px 14px;background:#f0f0f0;border-bottom:1px solid #e0e0e0;">
                    <strong style="font-size:14px;color:#555;">Riwayat Perbaikan</strong>
                </div>
                <div style="max-height:300px;overflow-y:auto;">${itemsHtml}</div>
                <div style="padding:10px 14px;background:#e8f4e8;border-top:2px solid #5a9a6e;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <strong style="font-size:13px;color:#2a5a3a;">Total Perbaikan:</strong>
                        <strong style="font-size:14px;color:#2a5a3a;">${totalPerbaikan} ${data.satuan ?? ''}</strong>
                    </div>
                    <div style="font-size:11px;color:#666;margin-top:4px;">${data.list_perbaikan.length} kali perbaikan tercatat</div>
                </div>
            </div>`;
    }

    return `
    <div style="font-family:sans-serif;width:400px;border-radius:10px;overflow:hidden;">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:12px 14px 8px;border-bottom:1px solid #eee;">
            <strong style="font-size:17px;">${data.nama_lokasi}</strong>
        </div>
        <div style="padding:12px 14px;">
            <div style="font-size:13px;color:#666;margin-bottom:2px;">
                Persentase Pulih: <strong style="float:right;font-size:16px;color:#111;">${pct}%</strong>
            </div>
            <div style="height:9px;border-radius:5px;background:#e0e0e0;margin:8px 0 12px;overflow:hidden;">
                <div style="background:#5a9a6e;height:100%;width:${pct}%;"></div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;font-size:14px;background:#f8f9fa;padding:10px;border-radius:8px;">
                ${iconUrl ? `<img src="${iconUrl}" style="width:32px;height:32px;object-fit:contain;">` : ''}
                <div>
                    <strong>${data.jumlah ?? ''}</strong> ${data.indikator?.parent?.nama ?? ''} ${data.indikator?.nama ?? ''} Terdampak Bencana
                </div>
            </div>
            ${buildPhotoCompareHtml(fotoAwal, fotoPulih)}
            <div style="font-size:12px;color:#666;margin-bottom:6px;margin-top:12px;">Wilayah : <br><span style="font-size:12px;color:#111;font-weight: 600">${namaWilayah ?? ''}</span></div>
            <div style="font-size:12px;color:#666;margin-bottom:6px;margin-top:6px;">Kondisi : <br><span style="font-size:12px;color:#111;font-weight: 600">${data.kondisi ?? ''}</span></div>
            <div style="font-size:12px;color:#666;margin-bottom:6px;margin-top:6px;">Status : <br><span style="font-size:12px;color:#111;font-weight: 600">${data.status ?? ''}</span></div>
            <div style="font-size:12px;color:#666;margin-bottom:6px;margin-top:6px;">Kondisi Awal : <br><span style="font-size:12px;color:#111;font-weight: 600">${data.kondisi_awal ?? ''}</span></div>
            <div style="font-size:12px;color:#666;margin-bottom:16px;margin-top:2px;">Keterangan : <br><span style="font-size:12px;color:#111;font-weight: 600">${data.keterangan ?? ''}</span></div>
        </div>
        ${riwayatHtml}
    </div>`;
}


function formatDate(dateString) {
    if (!dateString) return '-';

    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };

    return date.toLocaleDateString('id-ID', options);
}

// Popup for paket pekerjaan markers
function buildMarkerPopupPekerjaan(data) {
    const namaWilayah = [
        data.wilayah?.parent?.parent?.nama,
        data.wilayah?.parent?.nama,
        data.wilayah?.nama,
    ].filter(Boolean).join(' - ');

    const iconUrl = data.indikator?.icon3 ? ASSET_PATH + data.indikator.icon3 : '';
    let iconHtml = iconUrl ? `<img src="${iconUrl}" style="width:28px;height:28px;object-fit:contain;margin-right:10px;">` : '';

    return `
    <div style="font-family:sans-serif;width:400px;border-radius:10px;overflow:hidden;">
        <div style="padding:12px 14px 8px;border-bottom:1px solid #eee;display:flex;align-items:center;">
            ${iconHtml}
            <strong style="font-size:17px;">${namaWilayah}</strong>
        </div>
        <div style="padding:12px;border-bottom:1px solid #eee;">
            <p style="margin:0;font-size:14px;color:#2a4d7a;font-weight:600;">${data.nama ?? ''}</p>
            <p style="margin:4px 0 0;font-size:12px;color:#666;line-height:1.4;">${data.keterangan ?? ''}</p>
        </div>
        <div style="padding:12px 14px;">
            Nominal Pekerjaan: <strong style="float:right;">Rp ${add_commas(data.nominal)}</strong>
        </div>
        <div style="display:flex; padding:8px 14px; gap:8px;">
            <button onclick="detail_pekerjaan('${data.id}')" style="background:#2a4d7a;color:#fff;border:none;border-radius:5px;padding:6px 12px;font-size:12px;cursor:pointer;flex:1;">Detail</button>
            <button onclick="window.open('https://www.google.com/maps?q=${data.latitude},${data.longitude}')" style="background:#2a4d7a;color:#fff;border:none;border-radius:5px;padding:6px 12px;font-size:12px;cursor:pointer;flex:1;">Peta</button>
            <button onclick="window.open('https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=${data.latitude},${data.longitude}&heading=0&pitch=0&fov=100')" style="background:#2a4d7a;color:#fff;border:none;border-radius:5px;padding:6px 12px;font-size:12px;cursor:pointer;flex:1;">Streetview</button>
        </div>
    </div>`;
}

function buildMarkerPopupAnggaran(data) {
    const namaWilayah = [
        data.wilayah?.parent?.nama,
        data.wilayah?.nama,
    ].filter(Boolean).join(' - ');

    let listAlokasiHtml = '';
    if (data.list_alokasi && data.list_alokasi.length > 0) {
        let itemsHtml = data.list_alokasi.map((alokasi, index) => `
            <div style="padding:12px 14px;border-bottom:1px solid #e8e8e8;background:#fff;">
                <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:2px;">
                    <span style="font-size:11px;color:#2a4d7a;font-weight:600;flex:1;">${index + 1}. ${alokasi.keterangan || 'Tidak ada keterangan'}</span>
                </div>
                <div style="text-align:right;">
                    <span style="font-size:12px;color:#333;font-weight:700;">Rp. ${add_commas(alokasi.nominal || 0)}</span>
                </div>
            </div>`).join('');

        const totalNominal = data.list_alokasi.reduce((sum, alokasi) => sum + (alokasi.nominal || 0), 0);

        listAlokasiHtml = `
            <div style="border-top:2px solid #eee;background:#fafafa;">
                <div style="padding:4px 12px;background:#f0f0f0;border-bottom:1px solid #e0e0e0;">
                    <strong style="font-size:12px;color:#555;">Detail Alokasi Anggaran</strong>
                </div>
                <div style="max-height:250px;overflow-y:auto;">${itemsHtml}</div>
                <div style="padding:4px 14px;background:#e8f4f8;border-top:2px solid #4a9fd8;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <strong style="font-size:11px;color:#2a4d7a;">Total Alokasi:</strong>
                        <strong style="font-size:12px;color:#2a4d7a;">Rp. ${add_commas(totalNominal)}</strong>
                    </div>
                </div>
            </div>`;
    }

    return `
    <div style="font-family:sans-serif;width:400px;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);background:#fff;">
        <div style="background:linear-gradient(135deg, #e8f0e8 0%, #d4e8d4 100%);padding:8px 14px;text-align:center;border-bottom:2px solid #a8c9a8;">
            <span style="font-size:11px;color:#555;letter-spacing:0.5px;text-transform:uppercase;">${namaWilayah}</span>
        </div>
        <div style="padding:16px 14px;background:#fff;">
            <div style="margin-bottom:6px;display:flex;align-items:center;">
                <span style="width:12px;height:12px;background:#666;border-radius:50%;display:inline-block;margin-right:10px;"></span>
                <span style="font-size:12px;color:#333;">Alokasi Dana TA 2025 : <strong>Rp. ${add_commas(data.anggaran_2025 || 0)}</strong></span>
            </div>
            <div style="margin-bottom:6px;display:flex;align-items:center;">
                <span style="width:12px;height:12px;background:#666;border-radius:50%;display:inline-block;margin-right:10px;"></span>
                <span style="font-size:12px;color:#333;">Alokasi Dana TA 2026 : <strong>Rp. ${add_commas(data.anggaran_2026 || 0)}</strong></span>
            </div>
            <div style="margin-bottom:6px;display:flex;align-items:center;">
                <span style="width:12px;height:12px;background:#666;border-radius:50%;display:inline-block;margin-right:10px;"></span>
                <span style="font-size:12px;color:#333;">Penyesuaian TKD 2026 : <strong>Rp. ${add_commas(data.penyesuaian || 0)}</strong></span>
            </div>
            <div style="display:flex;align-items:center;">
                <span style="width:12px;height:12px;background:#666;border-radius:50%;display:inline-block;margin-right:10px;"></span>
                <span style="font-size:12px;color:#333;">Setelah Penyesuaian TKD 2026 : <strong>Rp. ${add_commas(data.anggaran_2026 + data.penyesuaian || 0)}</strong></span>
            </div>
        </div>
        ${listAlokasiHtml}
    </div>`;
}

function buildPhotoCompareHtml(fotoAwal, fotoPulih) {
    const photoSlot = (src, label) => {
        if (!src) {
            return `
            <div style="flex:1;position:relative;overflow:hidden;background:#f0f0f0;display:flex;align-items:center;justify-content:center;">
                <div style="font-size:11px;font-weight:600;color:#999;text-align:center;padding:8px;">Belum Dilaporkan</div>
            </div>`;
        }
        return `
        <div style="flex:1;position:relative;overflow:hidden;">
            <img src="${src}" style="width:100%;height:100%;object-fit:cover;cursor:pointer;" onclick="window.open('${src}', '_blank')">
            <div style="position:absolute;bottom:5px;left:0;right:0;text-align:center;font-size:10px;font-weight:700;color:#fff;letter-spacing:1px;text-shadow:0 1px 3px rgba(0,0,0,.6);">${label}</div>
        </div>`;
    };

    return `
    <div style="display:flex;border-radius:8px;overflow:hidden;position:relative;height:100px;">
        ${photoSlot(fotoAwal, 'SEBELUM')}
        <div style="position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);z-index:10;width:26px;height:26px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;color:#444;box-shadow:0 1px 5px rgba(0,0,0,.2);">›</div>
        ${photoSlot(fotoPulih, 'SESUDAH')}
    </div>`;
}
