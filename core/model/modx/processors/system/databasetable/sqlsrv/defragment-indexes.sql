/*
Added a @ViewOnly variable that will allow you to run this script as a test only and review proposed actions.
I also enhanced to perform either a reorg or rebuild, based on range of fragmentation value.
~Paul DeBrino : infinityrd.com

--INSPECT INDEXES' FILL FACTOR VALUES:
use YourDBName
select * from sys.indexes order by name

--VIEW THE DEFAULT FILL FACTOR OF YOUR SERVER:
use YourDBname
exec sp_configure 'show advanced options', 1
go
reconfigure
go
exec sp_configure 'fill factor'
go

*/
-- Specify your Database Name
--USE [[+dbname]]
--GO

-- Declare variables
SET NOCOUNT ON
DECLARE @tablename VARCHAR(128)
DECLARE @execstr VARCHAR(255)
DECLARE @objectid INT
DECLARE @indexid INT
DECLARE @frag decimal
DECLARE @maxreorg decimal
DECLARE @maxrebuild decimal
DECLARE @IdxName varchar(128)
DECLARE @ViewOnly bit
DECLARE @ReorgOptions varchar(255)
DECLARE @RebuildOptions varchar(255)

-- Set to 1 to view proposed actions, set to 0 to Execute proposed actions:
SET @ViewOnly=0

-- Decide on the maximum fragmentation to allow for a reorganize.
-- AVAILABLE OPTIONS: http://technet.microsoft.com/en-us/library/ms188388(SQL.90).aspx
SET @maxreorg = 20.0
SET @ReorgOptions = 'LOB_COMPACTION=ON'
-- Decide on the maximum fragmentation to allow for a rebuild.
SET @maxrebuild = 30.0
-- NOTE: only specifiy FILLFACTOR=x if x is something other than zero:
SET @RebuildOptions = 'PAD_INDEX=OFF, SORT_IN_TEMPDB=ON, STATISTICS_NORECOMPUTE=OFF, ALLOW_ROW_LOCKS=ON, ALLOW_PAGE_LOCKS=ON'

-- Declare a cursor.
DECLARE tables CURSOR FOR
SELECT CAST(TABLE_SCHEMA AS VARCHAR(100))
+'.'+CAST(TABLE_NAME AS VARCHAR(100))
AS Table_Name
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_TYPE = 'BASE TABLE'

-- Create the temporary table.
if exists (select name from tempdb.dbo.sysobjects where name like '#fraglist%')
drop table #fraglist

CREATE TABLE #fraglist (
ObjectName CHAR(255),
ObjectId INT,
IndexName CHAR(255),
IndexId INT,
Lvl INT,
CountPages INT,
CountRows INT,
MinRecSize INT,
MaxRecSize INT,
AvgRecSize INT,
ForRecCount INT,
Extents INT,
ExtentSwitches INT,
AvgFreeBytes INT,
AvgPageDensity INT,
ScanDensity decimal,
BestCount INT,
ActualCount INT,
LogicalFrag decimal,
ExtentFrag decimal)

-- Open the cursor.
OPEN tables

-- Loop through all the tables in the database.
FETCH NEXT
FROM tables
INTO @tablename

WHILE @@FETCH_STATUS = 0
BEGIN
-- Do the showcontig of all indexes of the table
INSERT INTO #fraglist
EXEC ('DBCC SHOWCONTIG (''' + @tablename + ''')
WITH FAST, TABLERESULTS, ALL_INDEXES, NO_INFOMSGS')
FETCH NEXT
FROM tables
INTO @tablename
END

-- Close and deallocate the cursor.
CLOSE tables
DEALLOCATE tables

-- Declare the cursor for the list of indexes to be defragged.
DECLARE indexes CURSOR FOR
SELECT ObjectName, ObjectId, IndexId, LogicalFrag, IndexName
FROM #fraglist
WHERE ((LogicalFrag >= @maxreorg) OR (LogicalFrag >= @maxrebuild))
AND INDEXPROPERTY (ObjectId, IndexName, 'IndexDepth') > 0

-- Open the cursor.
OPEN indexes

-- Loop through the indexes.
FETCH NEXT
FROM indexes
INTO @tablename, @objectid, @indexid, @frag, @IdxName

WHILE @@FETCH_STATUS = 0
BEGIN
IF (@frag >= @maxrebuild)
BEGIN
IF (@ViewOnly=1)
BEGIN
PRINT 'WOULD be executing ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REBUILD WITH ( ' + @RebuildOptions + ' ) -- Fragmentation currently ' + RTRIM(CONVERT(VARCHAR(15),@frag)) + '%'
END
ELSE
BEGIN
PRINT 'Now executing ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REBUILD WITH ( ' + @RebuildOptions + ' ) -- Fragmentation currently ' + RTRIM(CONVERT(VARCHAR(15),@frag)) + '%'
SELECT @execstr = 'ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REBUILD WITH ( ' + @RebuildOptions + ' )'
EXEC (@execstr)
END
END
ELSE IF (@frag >= @maxreorg)
BEGIN
IF (@ViewOnly=1)
BEGIN
PRINT 'WOULD be executing ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REORGANIZE WITH ( ' + @ReorgOptions + ' ) -- Fragmentation currently ' + RTRIM(CONVERT(VARCHAR(15),@frag)) + '%'
END
ELSE
BEGIN
PRINT 'Now executing ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REORGANIZE WITH ( ' + @ReorgOptions + ' ) -- Fragmentation currently ' + RTRIM(CONVERT(VARCHAR(15),@frag)) + '%'
SELECT @execstr = 'ALTER INDEX ' + RTRIM(@IdxName) + ' ON ' + RTRIM(@tablename) + ' REORGANIZE WITH ( ' + @ReorgOptions + ' )'
EXEC (@execstr)
END
END

FETCH NEXT
FROM indexes
INTO @tablename, @objectid, @indexid, @frag, @IdxName
END

-- Close and deallocate the cursor.
CLOSE indexes
DEALLOCATE indexes

-- Delete the temporary table.
DROP TABLE #fraglist
--GO
