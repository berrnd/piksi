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

			if (is_dir($folderPath))
			{
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
					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount,
					'audiosCount' => $audiosCount
				];
			}
		}

		return $folders;
	}

	public function GetFolder(int $folderIndex, string $subFolderPathRelative, bool $skipSubfolders = false)
	{
		$subFolderPathRelative = rtrim($subFolderPathRelative, '/');

		if (!array_key_exists($folderIndex, PIKSI_FOLDERS))
		{
			throw new \Exception('Invalid folder index ' . $folderIndex);
		}

		$folderPath = realpath(PIKSI_FOLDERS[$folderIndex]['path']);
		$subFolderPath = realpath($folderPath . '/' . ltrim($subFolderPathRelative, '/'));

		if (!string_starts_with($subFolderPath, $folderPath))
		{
			throw new \Exception($subFolderPathRelative . ' is not a subfolder of ' . $folderPath . ' or it doesn\'t exist');
		}

		$allFileExtensions = array_merge(PIKSI_PICTURE_FILEEXT, PIKSI_VIDEO_FILEEXT, PIKSI_AUDIO_FILEEXT);
		$items = [];
		foreach (new \DirectoryIterator($subFolderPath) as $file)
		{
			if ($file->isDot())
			{
				continue;
			}

			$fileExtension = strtolower($file->getExtension());

			if ($file->isDir())
			{
				if ($file->getFilename() == PIKSI_THUMBS_FOLDER_NAME)
				{
					continue;
				}

				$coverImagePathRelative = $subFolderPathRelative . '/' . $file->getFilename() . '/' . PIKSI_ALBUM_COVER_FILENAME;
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
					$subFolderInfo = $this->GetFolder($folderIndex, $subFolderPathRelative . '/' . $file->getFilename());
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

				$items[] = [
					'type' => 'folder',
					'sort_name' => '_a_' . $file->getFilename(),
					'name' => $file->getFilename(),
					'relativePath' => $subFolderPathRelative . '/' . $file->getFilename(),
					'coverImagePathRelative' => $coverImagePathRelative,
					'foldersCount' => $foldersCount,
					'picturesCount' => $picturesCount,
					'videosCount' => $videosCount,
					'audiosCount' => $audiosCount
				];
			}
			elseif ($file->isFile() && in_array($fileExtension, $allFileExtensions))
			{
				if ($file->getFilename() == PIKSI_ALBUM_COVER_FILENAME)
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

				$thumbRelativePath = $subFolderPathRelative . '/' . $file->getFilename();
				if ($type == 'picture' && file_exists(str_replace($file->getFilename(), PIKSI_THUMBS_FOLDER_NAME . '/' . $file->getFilename(), $file->getRealPath())))
				{
					$thumbRelativePath = $subFolderPathRelative . '/' . PIKSI_THUMBS_FOLDER_NAME . '/' . $file->getFilename();
				}

				$items[] = [
					'type' => $type,
					'name' => $file->getBasename('.' . $file->getExtension()),
					'sort_name' => '_b_' . $file->getFilename(),
					'relativePath' => $subFolderPathRelative . '/' . $file->getFilename(),
					'thumbRelativePath' => $thumbRelativePath
				];
			}
		}

		$sort = SORT_ASC;
		if (array_key_exists('sort', PIKSI_FOLDERS[$folderIndex]) && PIKSI_FOLDERS[$folderIndex]['sort'] == 'desc')
		{
			$sort = SORT_DESC;
		}

		array_multisort(array_column($items, 'sort_name'), $sort, $items);

		return $items;
	}
}
