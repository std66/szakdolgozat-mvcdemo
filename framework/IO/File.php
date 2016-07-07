<?php

/**
 * Fájlkezelésért felelős osztály
 * 
 * @author Sinku Tamás <sinkutamas@gmail.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 * @since 0.1
 */
class File {
	/**
	 * Megadja, hogy egy adott fájl létezik-e
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @return boolean True, ha a fájl létezik, false ha nem
	 * @since 0.1
	 */
	public static function exists($File) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		return file_exists(Path::fixSeparator($File));
	}
	
	/**
	 * Létrehoz egy fájlt a megadott névvel.
	 * 
	 * @param string $File A létrehozandó fájl neve a teljes elérési útvonallal
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @return boolean True, ha a fájlt sikerült létrehozni, false ha nem
	 * @since 0.1
	 */
	public static function create($File) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		return touch(Path::fixSeparator($File));
	}
	
	/**
	 * A megadott fájl tartalmát string-ként adja vissza.
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @return string A fájl tartalma
	 * @throws IOException Ha a fájl nem létezik vagy a tartalmát nem sikerült felolvasni
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @since 0.1
	 */
	public static function getContentsAsString($File) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		$FixedPath = Path::fixSeparator($File);
		
		if (!File::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik");
		}
		
		$Contents = file_get_contents($FixedPath);
		
		if ($Contents === false) {
			throw new IOException("A(z) $FixedPath fájlt nem sikerült felolvasni");
		}
		
		return $Contents;
	}
	
	/**
	 * A fájl tartalmát JSON-ként dolgozza fel és PHP objektumként adja vissza.
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @throws IOException Ha a fájl nem létezik, a tartalmát nem sikerült felolvasni vagy a tartalma nem értelmezhető JSON dokumentumként
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @return object A JSON dokumentum alapján létrehozott PHP objektum
	 * @since 0.1
	 */
	public static function getContentsAsJsonObject($File) {
		$JsonObject = json_decode(self::getContentsAsString($File));
		
		if ($JsonObject == NULL) {
			throw new IOException("A(z) $File nem dolgozható fel JSON-dokumentumként");
		}
		
		return $JsonObject;
	}
	
	/**
	 * A megadott fájlba beírja a megadott string-et. Ha a fájl létezik és nem
	 * üres, a tartalma felül fog íródni.
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @param string $Contents A fájlba írandó string
	 * @throws IOException Ha a fájl nem létezik vagy ha nem sikerült a tartalmat a fájlba írni
	 * @throws ArgumentNullException Ha a File vagy a Contents értéke NULL
	 * @return integer A fájlba írt adatmennyiség byte-ban kifejezve
	 * @since 0.1
	 */
	public static function putString($File, $Contents) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		if (!isset($Contents)) {
			throw new ArgumentNullException("File");
		}
		
		$FixedPath = Path::fixSeparator($File);
		if (!self::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik.");
		}
		
		$BytesWritten = file_put_contents($FixedPath, $Contents);
		if ($BytesWritten === false) {
			throw new IOException("A(z) $FixedPath fájlba nem sikerült beírni a tartalmat.");
		}
		
		return $BytesWritten;
	}
	
	/**
	 * A megadott fájl végéhez hozzáfűzi a megadott string-et.
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @param string $Contents A fájl végéhez írandó string
	 * @throws IOException Ha a fájl nem létezik vagy ha nem sikerült a tartalmat a fájlba írni
	 * @throws ArgumentNullException Ha a File vagy a Contents értéke NULL
	 * @return integer A fájlba írt adatmennyiség byte-ban kifejezve
	 * @since 0.1
	 */
	public static function appendString($File, $Contents) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		if (!isset($Contents)) {
			throw new ArgumentNullException("File");
		}
		
		$FixedPath = Path::fixSeparator($File);
		if (!self::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik.");
		}
		
		$BytesWritten = file_put_contents($FixedPath, $Contents, FILE_APPEND);
		if ($BytesWritten === false) {
			throw new IOException("A(z) $FixedPath fájlba nem sikerült beírni a tartalmat.");
		}
		
		return $BytesWritten;
	}
	
	/**
	 * Töröl egy fájlt.
	 * 
	 * @param string $File A fájl neve
	 * @return boolean True, ha sikerült törölni a fájlt, false ha nem
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @throws IOException Ha a törlendő fájl nem létezik
	 * @since 0.1
	 */
	public static function delete($File) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		$FixedPath = Path::fixSeparator($File);
		if (!self::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik.");
		}
		
		return unlink($FixedPath);
	}
	
	/**
	 * Megadja a megadott fájl méretét byte-ban kifejezve.
	 * 
	 * @param string $File A fájl neve
	 * @return integer A fájl mérete byte-ban kifejezve
	 * @throws ArgumentNullException Ha a File értéke NULL
	 * @throws IOException Ha a fájl nem létezik vagy nem sikerült meghatározni a méretét
	 * @since 0.1
	 */
	public static function getLength($File) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		$FixedPath = Path::fixSeparator($File);
		if (!self::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik.");
		}
		
		$SizeInBytes = filesize($FixedPath);
		if ($SizeInBytes === false) {
			throw new IOException("Nem állapítható meg a(z) $FixedPath fájl mérete.");
		}
		
		return $SizeInBytes;
	}
	
	/**
	 * Átnevez egy megadott fájlt. Ezzel a metódussal nem lehet fájlt áthelyezni,
	 * az átnevezés során a fájl az eredeti könyvtárában marad.
	 * 
	 * @param string $File A fájl neve a teljes elérési útvonallal
	 * @param string $NewName A fájl új neve a teljes elérési útvonal nélkül
	 * @throws ArgumentNullException Ha a File vagy a NewName értéke NULL
	 * @throws IOException Ha az átnevezendő fájl nem létezik, vagy az új néven már van létező fájl
	 * @return boolean True, ha sikerült a fájlt átnevezni, false ha nem
	 * @since 0.1
	 */
	public static function rename($File, $NewName) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		if (!isset($NewName)) {
			throw new ArgumentNullException("NewName");
		}
		
		$FixedPath = Path::fixSeparator($File);
		if (!self::exists($FixedPath)) {
			throw new IOException("A(z) $FixedPath fájl nem létezik.");
		}
		
		$Directory = pathinfo($FixedPath, PATHINFO_DIRNAME);
		
		$NewPath = $Directory . Environment::pathDelimiter() . $NewName;
		
		if (self::exists($NewPath)) {
			throw new IOException("A(z) $NewPath fájl már létezik");
		}
		
		return rename($FixedPath, $NewPath);
	}
	
	/**
	 * Áthelyezi a megadott fájlt a megadott könyvtárba. Ez a metódus nem teszi
	 * lehetővé a fájl átnevezését.
	 * 
	 * @param string $File Az áthelyezendő fájl az elérési útvonallal
	 * @param string $TargetDirectory A célkönyvtár az elérési útvonallal (végére nem szabad "/")
	 * @throws ArgumentNullException Ha a File vagy a TargetDirectory értéke NULL
	 * @throws IOException Ha a File vagy TargetDirectory nem létezik, vagy van ilyen nevű fájl a TargetDirectory könyvtárban
	 * @return boolean True, ha sikerült a fájl áthelyezése, false ha nem
	 * @since 0.1
	 */
	public static function move($File, $TargetDirectory) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		if (!isset($TargetDirectory)) {
			throw new ArgumentNullException("TargetDirectory");
		}
		
		$FixedFromPath = Path::fixSeparator($File);
		if (!self::exists($FixedFromPath)) {
			throw new IOException("A(z) $FixedFromPath fájl nem létezik.");
		}
		
		$FixedTargetPath = Path::fixSeparator($TargetDirectory);
		if (!is_dir($FixedTargetPath)) {
			throw new IOException("A(z) $FixedTargetPath könyvtár nem létezik.");
		}
		
		$Filename = pathinfo($FixedFromPath, PATHINFO_BASENAME);
		
		$NewPath = $FixedTargetPath . Environment::pathDelimiter() . $Filename;
		if (self::exists($NewPath)) {
			throw new IOException("A(z) $NewPath fájl már létezik");
		}
		
		return rename($FixedFromPath, $NewPath);
	}
	
	/**
	 * Másolatot készít a megadott fájlról a megadott könyvtárban.
	 * 
	 * @param string $File A másolandó fájl az elérési útvonallal
	 * @param string $TargetDirectory A célkönyvtár az elérési útvonallal (végére nem szabad "/")
	 * @throws ArgumentNullException Ha a File vagy a TargetDirectory értéke NULL
	 * @throws IOException Ha a File vagy TargetDirectory nem létezik, vagy van ilyen nevű fájl a TargetDirectory könyvtárban
	 * @return boolean True, ha sikerült a fájl áthelyezése, false ha nem
	 * @since 0.1
	 */
	public static function copy($File, $TargetDirectory) {
		if (!isset($File)) {
			throw new ArgumentNullException("File");
		}
		
		if (!isset($TargetDirectory)) {
			throw new ArgumentNullException("TargetDirectory");
		}
		
		$FixedFromPath = Path::fixSeparator($File);
		if (!self::exists($FixedFromPath)) {
			throw new IOException("A(z) $FixedFromPath fájl nem létezik.");
		}
		
		$FixedTargetPath = Path::fixSeparator($TargetDirectory);
		if (!is_dir($FixedTargetPath)) {
			throw new IOException("A(z) $FixedTargetPath könyvtár nem létezik.");
		}
		
		$Filename = pathinfo($FixedFromPath, PATHINFO_BASENAME);
		
		$NewPath = $FixedTargetPath . Environment::pathDelimiter() . $Filename;
		if (self::exists($NewPath)) {
			throw new IOException("A(z) $NewPath fájl már létezik");
		}
		
		return copy($FixedFromPath, $NewPath);
	}
}