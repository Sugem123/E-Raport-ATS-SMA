document.addEventListener("DOMContentLoaded", function () {

    const form = document.querySelector("#ModalInputGrade form");
    if (!form) return;

    form.addEventListener("submit", function (e) {

        const tugas = parseFloat(document.getElementById("tugas").value);
        const uts = parseFloat(document.getElementById("uts").value);
        const uas = parseFloat(document.getElementById("uas").value);

        const nilai = [
            { nama: "Nilai Tugas", value: tugas },
            { nama: "Nilai UTS", value: uts },
            { nama: "Nilai UAS", value: uas }
        ];

        for (const item of nilai) {

            if (isNaN(item.value)) {
                alert(item.nama + " harus diisi.");
                e.preventDefault();
                return;
            }

            if (item.value < 0 || item.value > 100) {
                alert(item.nama + " harus berada pada rentang 0 - 100.");
                e.preventDefault();
                return;
            }
        }

        const nilaiAkhir = document.getElementById("nilai_akhir").value;

        if (nilaiAkhir === "") {
            alert("Silakan hitung nilai akhir terlebih dahulu.");
            e.preventDefault();
            return;
        }
        
        if (nilaiAkhir > 100) {
                alert("Nilai akhir tidak boleh lebih dari 100.");
                e.preventDefault();
                return;
        }

    });

});
