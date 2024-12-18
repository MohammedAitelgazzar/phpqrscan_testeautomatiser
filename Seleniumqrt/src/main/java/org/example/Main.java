package org.example;

import io.github.bonigarcia.wdm.WebDriverManager;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.chrome.ChromeDriver;
import org.openqa.selenium.chrome.ChromeOptions;
import org.openqa.selenium.support.ui.WebDriverWait;
import org.openqa.selenium.support.ui.ExpectedConditions;
import java.time.Duration;

public class Main {
    public static void main(String[] args) {
        WebDriverManager.chromedriver().setup();

        ChromeOptions options = new ChromeOptions();
        options.addArguments("--start-maximized");
        options.addArguments("--use-fake-ui-for-media-stream"); // Autoriser automatiquement l'accès à la caméra

        WebDriver driver = new ChromeDriver(options);
        WebDriverWait wait = new WebDriverWait(driver, Duration.ofSeconds(30));

        try {
            // Première partie : Générer le QR Code
            System.out.println("Étape 1 : Génération du QR Code");
            driver.get("http://localhost:8000/index.php");

            WebElement nomInput = wait.until(ExpectedConditions.elementToBeClickable(By.id("nom")));
            nomInput.sendKeys("AIT EL GAZZAR");
            Thread.sleep(1000);

            WebElement prenomInput = driver.findElement(By.id("prenom"));
            prenomInput.sendKeys("Mohammed");
            Thread.sleep(1000);

            WebElement emailInput = driver.findElement(By.id("email"));
            emailInput.sendKeys("mohamedaitelgazzar@gmail.com");
            Thread.sleep(1000);

            WebElement telephoneInput = driver.findElement(By.id("telephone"));
            telephoneInput.sendKeys("0606652918");
            Thread.sleep(1000);

            WebElement photoInput = driver.findElement(By.id("photo"));
            photoInput.sendKeys("C:\\Etude5eme\\managment qualite\\formulairePHP\\uploads\\67632cc9115c0.jpeg");
            Thread.sleep(2000);

            System.out.println("Cliquer sur Générer QR Code...");
            WebElement submitButton = driver.findElement(By.cssSelector("input[type='submit']"));
            submitButton.click();

            // Attendre que le QR code s'affiche
            wait.until(ExpectedConditions.presenceOfElementLocated(By.className("qr-section")));
            Thread.sleep(5000); // Attendre 5 secondes pour voir le QR code

            // Deuxième partie : Test du Scanner QR Code avec upload de fichier
            System.out.println("Étape 2 : Test du Scanner QR Code avec fichier");
            driver.get("http://localhost:8000/scan_qr.php");
            Thread.sleep(2000);

            // Upload du QR code
            WebElement qrUpload = wait.until(ExpectedConditions.presenceOfElementLocated(By.id("fileSelector")));
            qrUpload.sendKeys("C:\\Etude5eme\\managment qualite\\formulairePHP\\uploads\\qrcode.png");

            // Attendre que les résultats s'affichent
            wait.until(ExpectedConditions.presenceOfElementLocated(By.id("result")));
            Thread.sleep(5000);

            // Vérifier si l'image et les informations sont affichées
            WebElement resultDiv = driver.findElement(By.id("result"));
            if (resultDiv.isDisplayed()) {
                System.out.println("Test avec fichier réussi : Informations et image affichées !");
            }

            // Troisième partie : Test du Scanner QR Code avec caméra
            System.out.println("Étape 3 : Test du Scanner QR Code avec caméra");
            driver.navigate().refresh(); // Actualiser la page
            Thread.sleep(2000);

            // Attendre que le scanner soit initialisé
            WebElement camerabutton=driver.findElement(By.id("html5-qrcode-button-camera-permission"));
            camerabutton.click();
            wait.until(ExpectedConditions.presenceOfElementLocated(By.id("reader")));
            System.out.println("Scanner de QR code activé. Veuillez présenter un QR code à la caméra...");

            // Attendre 30 secondes pour le test avec la caméra
            Thread.sleep(15000);

            System.out.println("Test terminé !");

        } catch (Exception e) {
            System.out.println("Erreur : " + e.getMessage());
            e.printStackTrace();
        } finally {
            driver.quit();
        }
    }
}

        // Create Workbook and Sheet
//        XSSFWorkbook workbook = new XSSFWorkbook();
//        XSSFSheet sheet = workbook.createSheet("Companies Data");
//
//        // Create header row
//        Row headerRow = sheet.createRow(0);
//        headerRow.createCell(0).setCellValue("Company Name");
//        headerRow.createCell(1).setCellValue("Secteur d'Activité");
//        headerRow.createCell(2).setCellValue("Forme Juridique");
//        headerRow.createCell(3).setCellValue("Capital");
//
//        int rowNum = 1; // Start from row 1 (after header)
//
//        try {
//            // First navigate to get total count
//            driver.get("https://www.charika.ma");
//            performSearch(driver, wait);
//
//            String companyXPath = "//h5[@class='strong text-lowercase truncate']/a[@class='goto-fiche']";
//
//            // Wait for initial results to load
//            wait.until(ExpectedConditions.presenceOfElementLocated(By.xpath(companyXPath)));
//
//            // Get total count of companies
//            List<WebElement> companyLinks = driver.findElements(By.xpath(companyXPath));
//            int totalCompanies = companyLinks.size();
//            System.out.println("Found " + totalCompanies + " companies to process");
//
//            for (int i = 0; i < totalCompanies; i++) {
//                try {
//                    System.out.println("\nProcessing company " + (i + 1));
//
//                    // Start fresh from homepage for each company
//                    driver.get("https://www.charika.ma");
//                    performSearch(driver, wait);
//
//                    // Wait for elements and click the i-th company
//                    wait.until(ExpectedConditions.presenceOfAllElementsLocatedBy(By.xpath(companyXPath)));
//                    WebElement companyLink = wait.until(ExpectedConditions.elementToBeClickable(
//                        driver.findElements(By.xpath(companyXPath)).get(i)
//                    ));
//
//                    String companyName = companyLink.getText();
//                    System.out.println("Clicking on company: " + companyName);
//
//                    // Click the company link
//                    companyLink.click();
//
//                    // Wait a moment for the page to load
//                    Thread.sleep(2000);
//
//                    // Extract company information with null handling
//                    String secteurActivite = "N/A";
//                    String formeJuridique = "N/A";
//                    String capital = "N/A";
//
//                    try {
//                        secteurActivite = driver.findElement(
//                            By.xpath("//*[@id=\"fiche\"]/div[1]/div[1]/div/div[2]/div[1]/div[2]/span/h2")).getText();
//                    } catch (Exception e) {
//                        System.out.println("Secteur d'Activité not found for: " + companyName);
//                    }
//
//                    try {
//                        formeJuridique = driver.findElement(
//                            By.xpath("//*[@id=\"fiche\"]/div[1]/div[1]/div/div[2]/div[4]/div/div[1]/table/tbody/tr[3]/td[2]")).getText();
//                    } catch (Exception e) {
//                        System.out.println("Forme Juridique not found for: " + companyName);
//                    }
//
//                    try {
//                        capital = driver.findElement(
//                            By.xpath("//*[@id=\"fiche\"]/div[1]/div[1]/div/div[2]/div[4]/div/div[1]/table/tbody/tr[4]/td[2]")).getText();
//                    } catch (Exception e) {
//                        System.out.println("Capital not found for: " + companyName);
//                    }
//
//                    // Create a new row in Excel and populate it
//                    Row row = sheet.createRow(rowNum++);
//                    row.createCell(0).setCellValue(companyName);
//                    row.createCell(1).setCellValue(secteurActivite);
//                    row.createCell(2).setCellValue(formeJuridique);
//                    row.createCell(3).setCellValue(capital);
//
//                    // Print for console tracking
//                    System.out.println("Added to Excel: " + companyName);
//                    System.out.println("----------------------------------------");
//
//                } catch (Exception e) {
//                    System.out.println("Error processing company " + (i + 1) + ": " + e.getMessage());
//                    e.printStackTrace();
//                    continue;
//                }
//            }
//
//            // Write the workbook to a file
//            try (FileOutputStream outputStream = new FileOutputStream("MarrakechCompanies.xlsx")) {
//                workbook.write(outputStream);
//            }
//            System.out.println("Excel file has been created successfully!");
//
//        } catch (Exception e) {
//            System.out.println("Error occurred: " + e.getMessage());
//            e.printStackTrace();
//        } finally {
//            try {
//                workbook.close();
//            } catch (IOException e) {
//                e.printStackTrace();
//            }
//            driver.quit();
//        }
//    }
//
//    private static void performSearch(WebDriver driver, WebDriverWait wait) {
//        // Click region selector
//        WebElement region = wait.until(ExpectedConditions.elementToBeClickable(
//            By.xpath("//*[@id=\"national\"]/form/div/div[2]/div/div/div/button/div/div/div")));
//        region.click();
//
//        // Enter city
//        WebElement ville = wait.until(ExpectedConditions.elementToBeClickable(
//            By.xpath("//*[@id=\"national\"]/form/div/div[2]/div/div/div/div/div[1]/input")));
//        ville.sendKeys("Marrakech");
//        ville.submit();
//    }
//}