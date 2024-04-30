function formatMontant(input) {
    // Retirer les espaces entre les chiffres
    let valueWithoutSpaces = input.value.replace(/\s/g, '');

    // Formater avec des espaces pour les milliers et conserver les virgules décimales
    input.value = Number(valueWithoutSpaces).toLocaleString('fr-FR');
}