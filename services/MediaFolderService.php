<?php

namespace Piksi\Services;

class MediaFolderService extends BaseService
{
	public function GetRootFolder()
	{
		$folders = [];

		for ($i = 0; $i < count(PIKSI_FOLDERS); $i++)
		{
			$folder = PIKSI_FOLDERS[$i];
			$folderPath = rtrim($folder['path'], '/');

			$isSpecial = false;
			if (array_key_exists('is_special', $folder))
			{
				$isSpecial = $folder['is_special'];
			}

			if (is_dir($folderPath))
			{
				if ($isSpecial)
				{
					continue;
				}

				$badgeText = '';
				if (array_key_exists('badge_text', $folder))
				{
					$badgeText = $folder['badge_text'];
				}

				$subFolderInfo = $this->GetFolder($i, '/', true);
				$foldersCount = 0;
				$picturesCount = 0;
				$videosCount = 0;
				$audiosCount = 0;
				foreach ($subFolderInfo as $info)
				{
					if ($info['type'] == 'picture')
					{
						$picturesCount++;
					}
					elseif ($info['type'] == 'video')
					{
						$videosCount++;
					}
					elseif ($info['type'] == 'audio')
					{
						$audiosCount++;
					}
					elseif ($info['type'] == 'folder')
					{
						$foldersCount++;
					}
				}

				$folders[] = [
					'name' => $folder['name'],
					'badgeText' => $badgeText,

					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount,
					'audiosCount' => $audiosCount
				];
			}
		}

		return $folders;
	}

	public function GetSpecialItems()
	{
		$items = [];

		for ($i = 0; $i < count(PIKSI_FOLDERS); $i++)
		{
			$folder = PIKSI_FOLDERS[$i];
			$folderPath = rtrim($folder['path'], '/');

			$isSpecial = false;
			if (array_key_exists('is_special', $folder))
			{
				$isSpecial = $folder['is_special'];
			}

			if (is_dir($folderPath))
			{
				if (!$isSpecial)
				{
					continue;
				}

				$items = array_merge($items, $this->GetFolder($i, '/', true));
			}
		}

		return $items;
	}

	public function GetFolder(int $folderIndex, string $subFolderPathRelative, bool $skipSubfolders = false)
	{
		$subFolderPathRelative = rtrim($subFolderPathRelative, '/');

		if (!array_key_exists($folderIndex, PIKSI_FOLDERS))
		{
			throw new \Exception('Invalid folder index ' . $folderIndex);
		}

		$folder = PIKSI_FOLDERS[$folderIndex];
		$folderPath = realpath($folder['path']);
		$subFolderPath = realpath($folderPath . '/' . ltrim($subFolderPathRelative, '/'));

		if (!string_starts_with($subFolderPath, $folderPath))
		{
			throw new \Exception($subFolderPathRelative . ' is not a subfolder of ' . $folderPath . ' or it doesn\'t exist');
		}

		$showFileNames = PIKSI_SHOW_FILENAMES;
		if (array_key_exists('show_filenames', $folder))
		{
			$showFileNames = $folder['show_filenames'];
		}

		$allFileExtensions = array_merge(PIKSI_PICTURE_FILEEXT, PIKSI_VIDEO_FILEEXT, PIKSI_AUDIO_FILEEXT);
		$items = [];
		foreach (new \DirectoryIterator($subFolderPath) as $file)
		{
			if ($file->isDot())
			{
				continue;
			}

			$fileName = $file->getFilename();
			$fileExtension = strtolower($file->getExtension());
			$fileNameWithoutExtension = $file->getBasename('.' . $file->getExtension());

			if ($file->isDir())
			{
				if ($fileName == PIKSI_THUMBS_FOLDER_NAME)
				{
					continue;
				}

				$coverImagePathRelative = $subFolderPathRelative . '/' . $fileName . '/' . PIKSI_ALBUM_COVER_FILENAME;
				if (!file_exists($file->getRealPath() . '/' . PIKSI_ALBUM_COVER_FILENAME))
				{
					$coverImagePathRelative = '';
				}

				$foldersCount = 0;
				$picturesCount = 0;
				$videosCount = 0;
				$audiosCount = 0;
				if (!$skipSubfolders)
				{
					$subFolderInfo = $this->GetFolder($folderIndex, $subFolderPathRelative . '/' . $fileName);
					foreach ($subFolderInfo as $info)
					{
						if ($info['type'] == 'picture')
						{
							$picturesCount++;
						}
						elseif ($info['type'] == 'video')
						{
							$videosCount++;
						}
						elseif ($info['type'] == 'audio')
						{
							$audiosCount++;
						}
						elseif ($info['type'] == 'folder')
						{
							$foldersCount++;
						}
					}
				}

				$alternativeSortTextFilePath = $file->getRealPath() . '/../' . $fileName . '.sort';
				$sortName = $fileName;
				if (file_exists($alternativeSortTextFilePath))
				{
					$sortName = file_get_contents($alternativeSortTextFilePath);
				}

				$badgeTextFilePath = $file->getRealPath() . '/../' . $fileName . '.badge';
				$badgeText = '';
				if (file_exists($badgeTextFilePath))
				{
					$badgeText = file_get_contents($badgeTextFilePath);
				}

				$items[] = [
					'folderIndex' => $folderIndex,
					'type' => 'folder',
					'sort_name' => 'aaaa' . $sortName,
					'name' => $fileName,
					'badgeText' => $badgeText,
					'relativePath' => $subFolderPathRelative . '/' . $fileName,
					'coverImagePathRelative' => $coverImagePathRelative,
					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount,
					'audiosCount' => $audiosCount,
					'show_filename' => $showFileNames
				];
			}
			elseif ($file->isFile() && in_array($fileExtension, $allFileExtensions))
			{
				if ($fileName == PIKSI_ALBUM_COVER_FILENAME)
				{
					continue;
				}

				$type = 'picture';
				if (in_array($fileExtension, PIKSI_VIDEO_FILEEXT))
				{
					$type = 'video';
				}
				elseif (in_array($fileExtension, PIKSI_AUDIO_FILEEXT))
				{
					$type = 'audio';
				}

				$thumbRelativePath = $subFolderPathRelative . '/' . $fileName;
				if ($type == 'picture' && file_exists(str_replace($fileName, PIKSI_THUMBS_FOLDER_NAME . '/' . $fileName, $file->getRealPath())))
				{
					$thumbRelativePath = $subFolderPathRelative . '/' . PIKSI_THUMBS_FOLDER_NAME . '/' . $fileName;
				}

				$badgeTextFilePath = str_replace($fileName, $fileNameWithoutExtension . '.badge', $file->getRealPath());
				$badgeText = '';
				if (file_exists($badgeTextFilePath))
				{
					$badgeText = file_get_contents($badgeTextFilePath);
				}

				$items[] = [
					'folderIndex' => $folderIndex,
					'type' => $type,
					'name' => $file->getBasename('.' . $file->getExtension()),
					'badgeText' => $badgeText,
					'sort_name' => 'bbbb' . $fileName,
					'relativePath' => $subFolderPathRelative . '/' . $fileName,
					'thumbRelativePath' => $thumbRelativePath,
					'show_filename' => $showFileNames
				];
			}
		}

		$sort = SORT_ASC;
		if (array_key_exists('sort', $folder) && $folder['sort'] == 'desc')
		{
			$sort = SORT_DESC;
		}

		array_multisort(array_column($items, 'sort_name'), $sort, $items);

		return $items;
	}
}
